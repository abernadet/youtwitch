<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AjaxController extends Controller
{
    /**
     * @Route("/user/ajax/twitch", name="twitch")
     */
    public function twitch(){
        return $this->render('ajax/twitch.html.twig');
    }

    /**
     * @Route("/user/ajax/youtube", name="youtube")
     */
    public function youtube(){
        return $this->render('ajax/youtube.html.twig');
    }
}
