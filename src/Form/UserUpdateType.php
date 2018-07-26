<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
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
        $builder
            ->add('username', TextType::class, array(
                'label' => 'Pseudo'
            ))
            ->add('email', EmailType::class)
            ->add('image', FileType::class, array('label' => 'Ajouter une image',
                                                    'attr' => ['class' => 'form-control-file'],
                                                    'required' => false ))
            ->add('twitchLogin', TextType::class, array('label' => 'Identifiant de votre chaine Twitch'))
            ->add('Modifier', SubmitType::class,array
            ('label' => 'Modifier', 'attr' => ['class' => 'btn btn-orange col-3']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
