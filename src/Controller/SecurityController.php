<?php

namespace App\Controller;

use App\Entity\LostPassword;
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
            $user->setYoutubeLogin(substr($form->getYoutbeLogin(),31,24));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('dark', 'Vous êtes bien inscrit, vous pouvez vous connecter !');
            return $this->redirectToRoute('login');
        }
        return $this->render('security/register.html.twig',
            array('form' =>$form->createView())
        );
    }

    /**
     * @Route("/reset-password", name="reset-password")
     */
    public function resetPassword(Request $request,\Swift_Mailer $mailer)
    {
        $email = $request->request->get('email', 0);
        if($email){
            //verifier que l'email existe dans ma bdd
            $repository=$this->getDoctrine()->getRepository(User::class);
            $user=$repository->searchEmail($email);
            //dump($user);
            if($user){
                //l'email existe
                $resetPass = new LostPassword();
                $token = md5(uniqid(rand(),true));
                //je rentre dans la bdd mon token et le user_id
                $resetPass->setToken($token);
                $resetPass->setUser($user['0']);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($resetPass);
                $entityManager->flush();
                $user_id = $user['0']->getId(); // avec getId je recupere l'element
                dump($user_id);
                $message = (new \Swift_Message('Modification du Mot de Passe'))
                    ->setFrom('youtwitch123@gmail.com')
                    ->setTo($email)
                    ->setBody(
                        $this->renderView(
                        // templates/emails/registration.html.twig
                            'security/resetPassword.html.twig',
                            array('token' => $token, 'idUser' => $user_id)
                        ),
                        'text/html'
                    );
                if ($mailer->send($message)) {
                    $this->addFlash('dark', "L'e-mail a bien été envoyé !");
                }else{
                    $this->addFlash('danger',"Erreur lors de l'envoi de l'e-mail veuillez verifier votre adresse e-mail.");
                }
            }else{
                $this->addFlash('danger',"Ton e-mail n'existe pas dons notre base de données.");
            }
        }
        return $this->render('user/askEmail.html.twig');
    }

    /**
     * @Route("/new-password", name="new-password")
     */
    public function newPassword(Request $request)
    {
        $token = $request->query->get('token', 0);
        $user = $request->query->get('iduser', 0);
        dump($token);
        dump($user);
        if($token && $user){
            //verifier que le token et le user id existe dans ma bdd
            $repository=$this->getDoctrine()->getRepository(LostPassword::class);
            $resetPassword=$repository->searchToken($token,$user);
            if(!$resetPassword){
                //token et id incorrects donc redirectionner
                $this->addFlash('danger',"Erreur !! Paramètres invalides");
                return $this->redirectToRoute('reset-password');
            }
        }else{
            $this->addFlash('danger',"ERREUR !!");
        }
        return $this->render('security/newPassword.html.twig', array('idUser'=>$user) );
    }

    /**
     * @Route("/ResetPasswordOk", name="reset-password-ok")
     */
    public function modifPassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        //ici je vais faire la modification de mot de passe
        $password = $request->request->get('password', 0);
        //metre en cript le mdp
        $idUser = $request->request->get('idUser');
        dump($idUser);
        $user = $this->getDoctrine()->getRepository(User::class )->find($idUser);
        if($user){
            $mdpEncoded = $encoder->encodePassword($user, $password);
        }
        if($password){
            $repository=$this->getDoctrine()->getRepository(User::class);
            $user=$repository->find($idUser);
            if($user){
                $user->setpassword($mdpEncoded);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('dark', "Mot de passe modifié !");
            }else{
                $this->addFlash('danger',"Le mot de passe n'a pa pu être modifié");
            }
        }
        return $this->render('security/passwordUpdate.html.twig');
    }
}
