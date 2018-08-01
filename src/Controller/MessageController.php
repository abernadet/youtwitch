<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use  Symfony\Component\HttpFoundation\Request;
use App\Entity\Message as Message;
use App\Form\MessageType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
    /**
     * @Route("/messages", name="all-messages")
     */

    public function showAll(){
        $repository = $this->getDoctrine()->getRepository(Message::class);
        $messages = $repository->findAll();
        return $this->render('message/messages.html.twig' ,
                            array('messages' => $messages));
    }
    //Appeler un show All pour faire afficher l'ensemble de mes Users
    //messagesSent et MessagesReceived se chargeront seulement de les afficher.

    /**
     * @Route("/message/{id}", name="message", requirements={"id"="\d+"} )
     */
    public function show($id){
        $repository= $this->getDoctrine()->getRepository(Message::class);
        $message = $repository->find($id);
        $message->setLu(true);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('message/message.html.twig', array('message' => $message));
    }
    /**
     * @Route("/message/add", name="message-add")
     */

    public function AddMessage(Request $request, TokenStorageInterface $tokenStorage){

        $message = new Message();

        $form= $this->createForm(MessageType::class,$message, array('security.token_storage' => $tokenStorage));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isvalid()){
            $message = $form->getData();
            $message->setDateenvoi(new \DateTime(date('Y-m-d H:i:s')));
            $message->setSender($this->getUser());
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            $this->addFlash('success', 'Message ajouté !');
            return $this->redirectToRoute('all-messages');
        }
        return $this->render('message/add.html.twig',
            array('form' => $form->createView()));
    }
    //un nouveau Addmessage changer $this->render
    //faire de l'ajax pour afficher un message envoyé

    /**
     * @Route ("/countmail", name="count-mail")
     */
    public function countMail(){
        $repo= $this->getDoctrine()->getRepository(Message::class);

        $count = $repo->countMail($this->getUser());

        return $this->render('message/countmail.html.twig',[
            'count' => $count
        ]);

    }
    /**
     * @Route ("/notifications", name="notifications")
     */
    public function notifications(){

    }

}
