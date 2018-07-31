<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\TwitchApiService;
use App\Service\TextFormatService;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        return $this->render('index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }



    function searchListByKeyword($service, $part, $params) {
        $params = array_filter($params);
        $response = $service->search->listSearch(
            $part,
            $params
        );

        print_r($response);
    }

    /**
     * @Route("/user/video", name="video")
     */

    public function video(UserInterface $user, TwitchApiService $twitch_api)
    {
        

        return $this->render('video.html.twig', [

        ]);
    }

    /**
     * @Route("/user/twitch_stream", name="twitch-stream")
     */
    public function playTwitchStream(TwitchApiService $twitch_api, TextFormatService $text_format, Request $request)
    {
        $params = $request->query->all();

        $login = '';
        if(!empty($params['login'])){
            $login = $params['login'];
            $clips = $twitch_api->getClipsFromChannel($login, 'week', 4);
        }else{
            $clips = [];
        }
        
        $user_data =$twitch_api->getUserFromLogin($login);
        $user_id = $user_data->data[0]->id;
        $user_display_name = $user_data->data[0]->display_name;

        $replays = $twitch_api->getVideosFromChannel($user_id, 'week', 4);

        $replay_tab = $replays->data;

        foreach($replay_tab as $replay)
        {
            $replay->thumbnail_url = $text_format->format_twitch_video_thumbnail_url($replay->thumbnail_url);
        }

        $stream_data = $twitch_api->getLiveStreamFromLogin($login);

        dump($stream_data->data[0]); 

        return $this->render('video_player/twitch_stream.html.twig', [
            'clips' => $clips,
            'login' => $login,
            'display_name' => $user_display_name,
            'replays' => $replay_tab,
            'stream_data' => $stream_data->data[0]
        ]);
    }

    /**
     * @Route("/user/twitch_clip", name="twitch-clip")
     */
    public function playTwitchClip(TwitchApiService $twitch_api, TextFormatService $text_format, Request $request)
    {
        $params = $request->query->all();
        $login = '';
        if(!empty($params['login']))
        {
            $login = $params['login'];
            $clips = $twitch_api->getClipsFromChannel($login, 'week', 4);
        }
        else
        {
            $clips = [];
        }
        
        $user_id = $twitch_api->getUserIdFromLogin($login);

        $replays = $twitch_api->getVideosFromChannel($user_id, 'week', 4);

        $replay_tab = $replays->data;



        foreach($replay_tab as $replay)
        {
            $replay->thumbnail_url = $text_format->format_twitch_video_thumbnail_url($replay->thumbnail_url);
        }
        
        dump($replay_tab);     

        return $this->render('video_player/twitch_clip.html.twig', [
            'params' => $params,
            'login' => $login,
            'clips' => $clips,
            'replays' => $replay_tab
        ]);
    }

    /**
     * @Route("/user/twitch_video", name="twitch-video")
     */
    public function playTwitchVideo(TwitchApiService $twitch_api, TextFormatService $text_format, Request $request)
    {
        $params = $request->query->all();
        $login = '';
        if(!empty($params['login']))
        {
            $login = $params['login'];
            $clips = $twitch_api->getClipsFromChannel($login, 'week', 4);
        }
        else
        {
            $clips = [];
        }
        if(!empty($params['vid_id']))
        {
            $vid_id = $params['vid_id'];
        }
        else{
            $vid_id = false;
        }
        
        $user_id = $twitch_api->getUserIdFromLogin($login);

        $replays = $twitch_api->getVideosFromChannel($user_id, 'week', 4);

        $replay_tab = $replays->data;



        foreach($replay_tab as $replay)
        {
            $replay->thumbnail_url = $text_format->format_twitch_video_thumbnail_url($replay->thumbnail_url);
        }

        dump($replay_tab); 

        return $this->render('video_player/twitch_video.html.twig', [
            'params' => $params,
            'clips' => $clips,
            'replays' => $replay_tab,
            'vid_id' => $vid_id,
            'login' => $login
        ]);
    }

    /**
     * @Route("/user/propos", name="propos")
     */

    public function aPropos()
    {
        return $this->render('propos.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/RGPD", name="rgpd")
     */
    public function rgpd()
    {
        return $this->render('rgpd.html.twig');
    }

}
