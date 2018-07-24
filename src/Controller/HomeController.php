<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    public function visionnage()
    {
        return $this->render('visionnage.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

}
