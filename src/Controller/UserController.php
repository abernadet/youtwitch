<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User as User;
use App\Form\UserType;
use App\Form\UserUpdateType;
use  Symfony\Component\HttpFoundation\Request;
use  Symfony\Component\HttpFoundation\File\File;
use App\Service\FileUploader;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/user/{id}", name="show-user", requirements={"id"="\d+"})
     */
    public function show(User $user){
        return $this->render('user/user.html.twig', array('user' => $user));
    }

    /**
     * @Route("/user/update/{id}", name="user-update", requirements={"id"="\d+"})
     */
    public function updateUser(User $user, Request $request, FileUploader $uploader){

        //$this->denyAccessUnlessGranted('edit', $user);
        if($user->getImage()){
            $user->setImage(new File($this->getParameter('users_images_directory') . '/' . $user->getImage()));
        }
        $form = $this->createForm(UserUpdateType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isvalid()){
            $user = $form->getData();
            if($user->getImage()){
                $file = $user->getImage();
                $fileName = $uploader->upload($file);
            }
            $user->setImage($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('succes', 'Profil Modifié ! ');
            return $this->redirectToRoute('login');
        }
        return $this->render('user/update.html.twig',
                array('form' => $form->createView())
        );
    }
}
