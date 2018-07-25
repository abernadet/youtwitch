<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\TwitchApiService;
use Symfony\Component\Security\Core\User\UserInterface;

class AjaxController extends Controller
{
    /**
     * @Route("/user/ajax/twitch", name="twitch")
     */
    public function twitch(UserInterface $user, TwitchApiService $twitch_api){
        $login = $user->getTwitchLogin();
        $user_id = $twitch_api->getUserIdFromLogin($login);
        $follows_id = $twitch_api->getUserFollowsId($user_id);
        $followed_users = $twitch_api->getUsersFromId($follows_id);

        $logins = [];
        foreach($followed_users as $channel)
        {
            $logins[] = $channel->login;
        }


        $clips = $twitch_api->getClipsFromChannels($logins, 'week');
        
        return $this->render('ajax/twitch.html.twig', [
            'clips' => $clips
        ]);
    }

    /**
     * @Route("/user/ajax/youtube", name="youtube")
     */
    public function youtube(){
        return $this->render('ajax/youtube.html.twig');
    }
}
