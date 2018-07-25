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
        // Create the Transport
        $transport = (new \Swift_SmtpTransport('user1@mail.com', 25))
            ->setUsername('user1')
            ->setPassword('mdp1')
        ;

        /*
        You could alternatively use a different transport such as Sendmail:

        // Sendmail
        $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
        */

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        // Create a message
        $message = (new \Swift_Message('Wonderful Subject'))
            ->setFrom(['user1@mail.com' => 'John Doe'])
            ->setTo(['maisonneuve@gmail.com' => 'A name'])
            ->setBody('Here is the message itself')
        ;

        // Send the message
        $result = $mailer->send($message);

        return $this->render('lost_password/registration.html.twig', [
            'controller_name' => 'LostPasswordController',
        ]);
    }
}
