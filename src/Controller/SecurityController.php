<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User as User;
use App\Form\UserType;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils )
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route ("/register", name="register")
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
            $user->setRoles(array("ROLE_USER"));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Vous êtes bien inscrit, vous pouvez vous connecter !');
            return $this->redirectToRoute('login');
        }
        return $this->render('security/register.html.twig',
            array('form' =>$form->createView())
        );
    }
}
