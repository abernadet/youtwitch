<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\TwitchApiService;
use Symfony\Component\Security\Core\User\UserInterface;

class IncludesController extends Controller
{
    /**
     * 
     */
    public function showSidebar(UserInterface $user, TwitchApiService $twitch_api)
    {
        $login = $user->getTwitchLogin();
        dump($login);

        if($user_id = $twitch_api->getUserIdFromLogin($login))
        {
            $follows_id = $twitch_api->getUserFollowsId($user_id);
            $follows = $twitch_api->getUsersFromId($follows_id);
        }
        else
        {
            $follows = [];
        }

        $live_streams = ($twitch_api->getLiveStreams($follows_id))->data;
        $live_streams_user_id = [];
        foreach($live_streams as $stream)
        {
            $live_streams_user_id[] = $stream->user_id;
        }

        dump($follows);

        return $this->render('includes/nav.html.twig', [
            'follows' => $follows,
            'live_streams' => $live_streams,
            'streams_user_id' => $live_streams_user_id
        ]);
    }
}
