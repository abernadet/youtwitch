<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserUpdateType;
use App\Form\AdminType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();
        return $this->render('admin/index.html.twig', array('users' => $users));
    }

    /**
     * @Route("/admin/mon-profil/{id}", name="admin-show-user", requirements={"id"="\d+"})
     */
    public function showUser(User $user){

        return $this->render('admin/user.html.twig', array('user' => $user));
    }

    /**
     * @Route("/admin/add", name="admin-add-user")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $encoder, FileUploader $uploader){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $fileName = "";

        $user = new User();

        $form = $this->createForm(AdminType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $roles = $request->request->get('roles');
            dump($roles);
            $user->setRoles($roles);

            $mdpEncoded = $encoder->encodePassword($user,$user->getPlainPassword());
            $user->setPassword($mdpEncoded);
            $user->eraseCredentials();

            # Je ne fais le traitement d'upload que si on m'a envoyé un fichier
            if ($user->getImage()) {
                # Ceci va contenir l'image envoyée
                $file = $user->getImage();
                # On génère un nouveau nom
                $fileName = $uploader->upload($file);
            }
            # On met à jour la propriété image, qui doit contenir le nom du fichier et pas le fichier lui même pour pouvoir persister l'article
            $user->setImage($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur ajouté !');

            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/add.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("admin/update/{id}", name="admin-user-update", requirements={"id"="\d+"})
     */
    public function updateUser(User $user, Request $request, FileUploader $uploader){
        $fileName = $user->getImage(); # Je stocke le nom du fichier

        if($user->getImage()){
            # Pour pouvoir générer le formulaire, on doit transformer le nom du fichier stocké pour l'instant dans l'attribut image en instance de la classe File
            #(ce qui est attendu par le formulaire)
            $user->setImage(new File($this->getParameter('articles_image_directory') . '/' . $user->getImage()));
        }

        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            # Je ne fais le traitement d'upload que si on m'a envoyé un fichier
            if ($user->getImage()){
                $file = $user->getImage(); # On récupère un objet de classe File
                $fileName = $uploader->upload($file, $fileName);
            }

            # $filename contient soit le nouveau nom de fichier si on a reçu une nouvelle image, soit l'ancien si l'utilisateur n'a rien envoyé
            $user->setImage($fileName); # On met à jour la propriété image qui doit contenir le nom du fichier pour être persisté

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié !');

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/update.html.twig', array('form' => $form->createView()));
    }
}