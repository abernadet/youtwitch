<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\User;
use App\Form\LostPasswordType;

class LostPasswordController extends Controller
{
    private $oldpassword;
    private $newpassword;

    /**
     * @Route("/change-password/{email}", name="change_password",defaults={"email=null"})
     * @Method({"GET","POST"})
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param $email
     * @return Response
     */
    public function LostPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, $email){
        $user = new User();
        $form = $this->createForm(LostPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $user = $this->getDoctrine()->getRepository(User::class)->find($email);

            } catch (ExceptionInterface $e) {
                $this->addFlash('danger', "Cet email n'existe pas.");
            }

            $user->getEmail();
            //Recuperer le nouveau mot de passe tapé par l'utilisateur
            $newpassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            //recuperer l'ancien mot de passe dans la base de donnéees
            $oldpassword = $user->getPassword();

            if ($newpassword == $oldpassword) {
                $this->addFlash('danger', "Ce mot de passe est dejà utilisé.");

            } else {
                $user->setPassword($newpassword);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'votre mot de passe est bien  réinitialisé');

            # Redirection sur la page de connexion
            return $this->redirectToRoute('connexion');
        }
        return $this->render(
            'lost_password/changemotdepasse.html.twig',
            array('form' => $form->createView())
        );
    }

     /**
      * @Route("/lost/password", name="lost_password")
     */
    public function index()
    {
        return $this->render('lost_password/index.html.twig', [
            'controller_name' => 'LostPasswordController',
        ]);
    }
}
