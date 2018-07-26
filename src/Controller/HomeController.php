<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\TwitchApiService;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @Route("/user/visionnage", name="visionnage")
     */
    public function visionnage(TwitchApiService $twitch_api)
    {
        $login = '';
        if(!empty($_GET['login'])){
            $login = $_GET['login'];
            $clips = $twitch_api->getClipsFromChannel($login, 'week', 4);
        }else{
            $clips = [];
        }

        dump($clips);

        return $this->render('visionnage.html.twig', [
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

}
