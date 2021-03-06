<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // on stocke les années à afficher dans le formulaire
        // pour le champ date de naissance dans un tableau
        for($i=date('Y');$i>=1900; $i--){
            $birthdate[] = $i;
        }

        $builder
            ->add('username', TextType::class, array('label' => 'Choisir le pseudo*', 'attr'=>['placeholder' => 'Pseudo']))
            ->add('email', EmailType::class, array('label' => 'Adresse E-Mail*', 'attr'=>['placeholder' => 'E-Mail']))
            ->add('plainPassword', RepeatedType::class, array('type' => PasswordType::class,
                'invalid_message'=> 'les mots de passe ne sont pas identiques',
                'first_options' => ['label' => 'Mot de passe*', 'attr'=>['placeholder' => 'Mot de passe']],
                'second_options' => ['label' => 'Répétez le mot de passe*', 'attr'=>['placeholder' => 'Répétez le mot de passe']]
            ))
            ->add('birthdate', BirthdayType::class, array('label' => 'Votre date de naissance*'))
            ->add('image', FileType::class, array('label' => 'Ajouter une image SVP', 'required' => false))
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter', 'attr' => ['class' => 'btn btn-orange col-3']));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
