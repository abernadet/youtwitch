<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Chat;
use App\Form\ChatType;
use  Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;


class ChatController extends Controller
{
    /**
     * @Route("/chat", name="chat")
     */
    public function index()
    {
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
        ]);
    }
    /**
     * @Route("/chat/add", name="chat-add")
     */
    public function AddChat(Request $request){
        $chat = new Chat();

        $message = $request->request->get('message', null);

            $chat->setDateEnvoie(new \DateTime(date('Y-m-d H:i:s')));
            $chat->setMessage($message);
            $chat->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chat);
            $entityManager->flush();
            //return $this->redirectToRoute('chat-add');


        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Chat::class);
        $chats = $repository->findAll();
        dump($chats);

        return $this->render('chat/resultchat.html.twig', array('chats'=> $chats));
    }
    /**
     * @Route("/chat/", name="chat")
     */
    /*public function retourneMessage(){
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Chat::class);
        $chats = $repository->find($user->getUsername());
        return $this->render('chat/resultchat.html.twig', array('chats'=> $chats));
    }*/
}
