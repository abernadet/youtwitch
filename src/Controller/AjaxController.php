<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\TwitchApiService;
use App\Service\TextFormatService;
use Symfony\Component\Security\Core\User\UserInterface;

class AjaxController extends Controller
{
    /**
     * @Route("/user/ajax/twitch", name="twitch")
     */
    public function twitch(UserInterface $user, TwitchApiService $twitch_api, TextFormatService $text_format){
        
        $displayed_follows = 3;

        $login = $user->getTwitchLogin();
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
        return $this->render('ajax/youtube.html.twig');
    }
}
