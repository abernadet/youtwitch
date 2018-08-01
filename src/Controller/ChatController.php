<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Chat;
use App\Form\ChatType;
use  Symfony\Component\HttpFoundation\Request;



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
