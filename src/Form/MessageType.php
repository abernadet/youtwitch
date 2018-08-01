<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class MessageType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id = $options['security.token_storage']->getToken()->getUser()->getId();
        $builder
            //créer une requête custome dans le repository User, selectionner tous les users sauf les connectés
            ->add('recipient', EntityType::class,
                array('class' => User::class, 'query_builder'=> function(EntityRepository $repo) use($id){
                    return $repo->createQueryBuilder('u')
                        ->andWhere('u.id != :user')
                        ->setParameter('user', $id);
                }
                    ))
            ->add('sujet', TextType::class)
            ->add('contenu', TextareaType::class)
            ->add('ajouter', SubmitType::class,array
                    ('label' => 'Envoyer', 'attr' => ['class' =>'btn btn-orange']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
        $resolver->setRequired('security.token_storage');
    }
}
