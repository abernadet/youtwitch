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
     * @Route("/message", name="message-list")
     */
    public function index()
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
    /**
     * @Route("/user/messages", name="all-messages")
     */

    public function showAll(){
        $repository = $this->getDoctrine()->getRepository(Message::class);
        $recieved_messages = $repository->findBy(['recipient' => $this->getUser()]);
        $sent_messages = $repository->findBy(['sender' => $this->getUser()]);
        return $this->render('message/messages.html.twig' ,
                            array('sent_messages' => $sent_messages, 'received_messages' => $recieved_messages));
    }
    //Appeler un show All pour faire afficher l'ensemble de mes Users
    //messagesSent et MessagesReceived se chargeront seulement de les afficher.

    /**
     * @Route("/user/message/{id}", name="message", requirements={"id"="\d+"} )
     */
    public function show($id){
        $repository= $this->getDoctrine()->getRepository(Message::class);
        $message = $repository->find($id);
        $message->setLu(true);
        $this->getDoctrine()->getManager()->flush();
        dump($message);
        return $this->render('message/message.html.twig', array('message' => $message));
    }
    ########## JE N'ARRIVE PAS A FAIRE LA METHODE POUR AFFICHER TOUS LES MESSAGES DE LA CONVERSATION ##########
    /*public function show(Message $message,Request $request){
        if($message) {
            $repository = $this->getDoctrine()->getRepository(Message::class);
            $messages = $repository->findBySujet($message->getSujet());
            dump($messages);
            if ($messages) {
                $message = $repository->find($id);
                $message->setLu(true);
                $this->getDoctrine()->getManager()->flush();
            }
        }
        return $this->render('message/message.html.twig', array('conversation' => $conversation));
    }*/
    ############################################################################################################

    /**
     * @Route("/user/message/add", name="message-add")
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
            $this->addFlash('success', 'Message envoyé !');
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
        $count = $repo->countMail2($this->getUser());
        return $this->render('message/countmail.html.twig',[
            'count' => $count
        ]);

    }
    //nouveau controller sans route compter le nombre de nouveaux mesages pour l'utilisateur connecté
//renvoie une vue qui va afficher le nombre de message
//Layout je veux executer un controller(regarder sur la doc de twig)

}
