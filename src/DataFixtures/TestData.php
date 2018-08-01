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
            $mdpEncoded = $this->encoder->encodePassword($user, $plainPassword);

            # On va générer une date aléatoire
            # Generate a timestamp using mt_rand
            $timestamp = mt_rand(1, time());
            # Format that timestamp into a readable date string
            $randomDate = date("Y-m-d", $timestamp);
            # On l'envoie dans l'article
            $user->setBirthdate(new \DateTime($randomDate));

            $user->setPassword($mdpEncoded);
            if($user->getUsername() == 'user1'){
                $user->setRoles(array('ROLE_USER', 'ROLE_ADMIN'));
            }
            else{
                $user->setRoles(array('ROLE_USER'));
            }
            $user->setTwitchLogin('zerator');
            
            $manager->persist($user);
        }

        $user = new User();
        $user->setUsername('WF3');
        $user->setEmail('wf3project2018@gmail.com');

        $plainPassword = 'poisson';
        $mdpEncoded = $this->encoder->encodePassword($user, $plainPassword);

        $timestamp = mt_rand(1, time());
        $randomDate = date("Y-m-d", $timestamp);
        $user->setBirthdate(new \DateTime($randomDate));
        $user->setPassword($mdpEncoded);
        $user->setRoles(array('ROLE_USER', 'ROLE_ADMIN'));
        $user->setTwitchLogin('wf3_project_2018');

        $manager->persist($user);

        $manager->flush();
    }

}