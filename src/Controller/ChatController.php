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


        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Chat::class);
        $chats = $repository->findBy([], ['date_envoie' => 'DESC'], 20);
        $chats = array_reverse($chats);

        return $this->render('chat/resultchat.html.twig', array('chats'=> $chats));
    }

    /**
     * @Route("/chat/display", name="chat-display")
     */
    public function displayChat(){

        $repository = $this->getDoctrine()->getRepository(Chat::class);
        $chats = $repository->findBy([], ['date_envoie' => 'DESC'], 20);
        $chats = array_reverse($chats);

        return $this->render('chat/resultchat.html.twig', array('chats'=> $chats));


        $form= $this->createForm(chatType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isvalid()){
            $chat =$form->getData();
            $chat->setDateenvoi(new \DateTime(date('Y-m-d H:i:s')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chat);
            $entityManager->flush();
            $this->addFlash('success', 'Message envoyé');
            return $this->redirectToRoute('chat-add');
        }
        return $this->render('chat/chat.html.twig',
                array('form' => $form->createView()));
    }
    /**
     * @Route("/chat/{id}", name="chat", requirements={"id"="\d+"})
     */
    public function show($id){
        $repository = $this->getDoctrine()->getRepository(Chat::class);
        $chat = $repository->find($id);
        return $this->render('chat/chat.html.twig', array('chat'=> $chat));

    }
}
