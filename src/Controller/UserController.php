<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User as User;
use App\Form\UserType;
use  Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route ("/user/add", name="register")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $encoder){
        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isvalid()){
            $user = $form->getData();
            $mdpEncoded = $encoder->encodePassword($user,$user->getPlainPassword());
            $user->setPassword($mdpEncoded);
            $user->eraseCredentials();
            $user->setRoles(array("ROLES_USER"));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Vous êtes bien inscrit, vous pouvez vous connecter !');
            return $this->redirectToRoute('login');
        }
        return $this->render('user_form/add.html.twig',
            array('form' =>$form->createView())
        );
    }
}
