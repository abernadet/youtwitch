<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\TwitchApiService;
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

    public function video()
    {
        return $this->render('video.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /*
     * @Route("/twitch", name="twitch")
     */
    /*public function twitch()
    {
        return $this->render('twitch.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }*/

    /**
     * @Route("/user/twitch_stream", name="twitch-stream")
     */
    public function playTwitchStream(TwitchApiService $twitch_api, Request $request)
    {
        $params = $request->query->all();

        $login = '';
        if(!empty($params['login'])){
            $login = $params['login'];
            $clips = $twitch_api->getClipsFromChannel($login, 'week', 4);
        }else{
            $clips = [];
        }

        $user_id = $twitch_api->getUserIdFromLogin($login);

        $replays = $twitch_api->getVideosFromChannel($user_id);

        dump($replays);

        return $this->render('video_player/twitch_stream.html.twig', [
            'clips' => $clips,
            'login' => $login
        ]);
    }

    /**
     * @Route("/user/twitch_clip", name="twitch-clip")
     */
    public function playTwitchClip(TwitchApiService $twitch_api, Request $request)
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

        return $this->render('video_player/twitch_clip.html.twig', [
            'params' => $params,
            'login' => $login,
            'clips' => $clips
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
