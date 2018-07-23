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

    /**
     * @Route("/video", name="video")
     */
    public function video()
    {
        return $this->render('video.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
