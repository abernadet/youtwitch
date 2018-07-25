<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LostPasswordController extends Controller
{
    /**
     * @Route("/lost/password", name="lost_password")
     */
    public function index()#$name, \Swift_Mailer $mailer
    {
        /*$message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    array('name' => $name)
                ),
                'text/html'
            )
        ;

        $mailer->send($message);*/

        return $this->render('lost_password/registration.html.twig', [
            'controller_name' => 'LostPasswordController',
        ]);
    }
}
