<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\TwitchApiService;
use App\Service\TextFormatService;
use Symfony\Component\Security\Core\User\UserInterface;

class AjaxController extends Controller
{
    private $Ykey = 'AIzaSyC14ed967GfZtOwI8D98w7v0-3yjdpQx9M';
    /**
     * @Route("/user/ajax/twitch", name="twitch")
     */
    public function twitch(UserInterface $user, TwitchApiService $twitch_api, TextFormatService $text_format){

        $displayed_follows = 3;

        $login = $user->getTwitchLogin();

        if($login != null)
        {
            $user_id = $twitch_api->getUserIdFromLogin($login);
            $follows_id =  $twitch_api->getRandomUserFollowsId($user_id, $displayed_follows);
            $followed_users = $twitch_api->getUsersFromId($follows_id);

            $live_streams = ($twitch_api->getLiveStreams($follows_id))->data;
            $live_streams_user_id = [];
            foreach($live_streams as $stream)
            {
                $live_streams_user_id[] = $stream->user_id;
            }


            $clips = [];
            $replays = [];
            $data = [];

            foreach($followed_users as $followed_user)
            {

                $clips_full = $twitch_api->getClipsFromChannel($followed_user->login, 'month', 4);
                $clips[$followed_user->login] = $clips_full->clips;
                $replays[$followed_user->login] = $twitch_api->getVideosFromChannel($followed_user->id, 'month', 4, 'time');

                $data[$followed_user->login] = [
                    'clip_data' => $clips[$followed_user->login],
                    'replay_data' => $replays[$followed_user->login]->data,
                    'display_name' => $followed_user->display_name,
                    'user_id' => $followed_user->id,
                    'login' => $followed_user->login,
                ];

                foreach($data[$followed_user->login]['replay_data'] as $replay)
                {
                    $replay->thumbnail_url = $text_format->format_twitch_video_thumbnail_url($replay->thumbnail_url);
                }
            }


        }
        else
        {
            $data = null;
            $live_streams_user_id = null;
            $followed_users = null;
        }

        return $this->render('ajax/twitch.html.twig', [
            'data' => $data,
            'streams_user_id' => $live_streams_user_id,
            'followed_users' => $followed_users
        ]);
    }

    /**
     * @Route("/user/ajax/youtube", name="youtube")
     */
    public function youtube(){
        $dateJour = new \DateTime(date('Y-m-d'));
        $dateJour = $dateJour->format('Y-m-d');
        $dateHier = new \DateTime(date('Y-m-d'));
        $dateHier=$dateHier->modify('-1 day');
        $dateHier=$dateHier->format('Y-m-d');

        $urlLasteDay = "https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=4&order=viewCount&publishedAfter=".$dateHier."T00%3A00%3A00Z&publishedBefore=".$dateJour."T00%3A00%3A00Z&key=$this->Ykey";
        $urlBestAllTime = "https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=2&order=viewCount&publishedAfter=1900-01-01T00%3A00%3A00Z&type=video&key=$this->Ykey";

        try {
            $json = file_get_contents($urlLasteDay);
            $obj = json_decode($json);
            $BestYVids = $obj->items;
            foreach($BestYVids as $BestYVid){
                $idVideos[] = $BestYVid->id->videoId;
            }
        } catch (\Exception $e) {
            $BestYVids = [];
            $idVideos = [];

        }
        foreach ($idVideos as $idVideo) {
            $urls3[] = "https://www.googleapis.com/youtube/v3/videos?part=snippet%2C+statistics&id=$idVideo&key=$this->Ykey";
        }
        try {
            foreach ($urls3 as $url3) {
                $json3 = file_get_contents($url3);
                $obj3 = json_decode($json3);
                $details3[] = $obj3->items;
                // dump($details3);
            }
        } catch (\Exception $e) {
            $details3 = [];
        }
        if (empty($BestYVids)){
            $text='c\'est vide';
        }else{
            $text = 'ya un truc';
        }

        try {
            $json2 = file_get_contents($urlBestAllTime);
            $obj2 = json_decode($json2);
            $BestYVidsAllTime = $obj2->items;
        } catch (\Exception $e) {
            $BestYVidsAllTime = [];

        }
        if (empty($BestYVidsAllTime)){
            $text='c\'est vide';
        }else{
            $text = 'ya un truc';
        }
        return $this->render('ajax/youtube.html.twig',[
            'BestYVids'=>$BestYVids,
            'BestYVidsAllTime'=>$BestYVidsAllTime,
            'urlLasteDay'=>$urlLasteDay,
            'details3'=>$details3
        ]);
    }
}
