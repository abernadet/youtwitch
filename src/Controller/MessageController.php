<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use  Symfony\Component\HttpFoundation\Request;
use App\Entity\Message as Message;
use App\Form\MessageType;

class MessageController extends Controller
{
    /**
     * @Route("/message", name="message")
     */
    public function index()
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

}
