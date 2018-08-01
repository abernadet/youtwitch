<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // on stocke les années à afficher dans le formulaire
        // pour le champ date de naissance dans un tableau
        for($i=date('Y');$i>=1900; $i--){
            $birthdate[] = $i;
        }

        $builder
            ->add('username', TextType::class, array(
                'label' => 'Pseudo'
            ))
            ->add('email', EmailType::class)
            ->add('phone', TextType::class, array('label' => 'Votre numéro de téléphone', 'required' => false))
            ->add('address', TextType::class, array('label' => 'Votre adresse', 'required' => false))
            ->add('birthdate', BirthdayType::class, array('label' => 'Votre date de naissance'))
            ->add('image', FileType::class, array('label' => 'Ajouter une image', 'required' => false ))
            ->add('twitchLogin', TextType::class, array('label' => 'Identifiant de votre chaine Twitch', 'required' => false))
            ->add('YoutubeLogin', TextType::class, array('label' => 'Nom de votre chaine Youtube', 'required' => false))
            ->add('Modifier', SubmitType::class,array
            ('label' => 'Modifier', 'attr' => ['class' => 'btn btn-orange col-3 text-center']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
