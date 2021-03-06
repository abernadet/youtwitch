<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\TwitchApiService;

class TwitchController extends Controller
{
    //ROUTES

    /**
     * @Route("/testtwitch/{login}", name="testtwitch")
     */

    public function showFollowedChannels($login, TwitchApiService $twitch_api)
    {

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

        return $this->render('twitch/index.html.twig', [
            'follows' => $follows,
            'live_streams' => $live_streams,
            'streams_user_id' => $live_streams_user_id
        ]);
    }
}