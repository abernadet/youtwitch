<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TestData extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){

        $this->encoder = $encoder;

    }

    public function load(ObjectManager $manager)
    {
        //Créer 10 utilisateur
        for($i=1;$i<=10;$i++){
            $user = new User();

            $user->setUsername('user'.$i);
            $user->setEmail('user'.$i.'@mail.com');

            $plainPassword = 'mdp' . $i;
            //j'utilise l'encoder pour être sur d'encode avec la bonne méthode (définie dans config/packages/security.yaml) le mot de passe
            $mdpEncoded = $this->encoder->encodePassword($user, $plainPassword);
            //j'envoie dans mon attribu password
            $user->setPassword($mdpEncoded);
            if($user->getUsername() == 'user1'){
                $user->setRoles(array('ROLE_USER', 'ROLE_ADMIN'));
            }
            else{

                $user->setRoles(array('ROLE_USER'));
            }
            $user->setIsActive(true);
            
            $manager->persist($user);
        }
    }

}