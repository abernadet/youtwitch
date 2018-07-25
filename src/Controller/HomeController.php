<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use  Symfony\Component\HttpFoundation\Request;
use App\Entity\Message as Message;
use App\Form\MessageType;


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
    public function visionnage(Request $request)
    {

        $repository = $this->getDoctrine()->getRepository(Message::class);
        $messages = $repository->findAll();

        $message = new Message();
        $form = $this->createForm(MessageType::class,$message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $message = $form->getData();
            $message->setDateenvoi(new \DateTime(date('Y-m-d H:i:s')));
            $message->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            $this->addFlash('success', 'commentaire ajouté ! ');
            return $this->redirectToRoute('visionnage');
        }
        return $this->render('visionnage.html.twig',
            array('form' => $form->createView(), 'messages' => $messages));
    }


}
