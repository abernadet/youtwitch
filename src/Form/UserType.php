<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array('label' => 'Choisir le pseudo'))
            ->add('email', EmailType::class, array('label' => 'Adresse E-Mail'))
            ->add('plainPassword', RepeatedType::class, array('type' => PasswordType::class,
                'invalid_message'=> 'les mots de passe ne sont pas identiques',
                'first_options' => ['label' => 'Mot de passe', 'attr'=>['placeholder' => 'Mot de passe']],
                'second_options' => ['label' => 'Répétez le mot de passe', 'attr'=>['placeholder' => 'Répétez le mot de passe']]
            ))
            ->add('twitchLogin', TextType::class, array(
                'label' => 'Identifiant de votre chaine Twitch'))
            ->add('ajouter', SubmitType::class,array
            ('label' => 'Inscrivez-vous !', 'attr' => ['class' => 'btn btn-orange col-4']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
