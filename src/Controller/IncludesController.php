<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\TwitchApiService;
use Symfony\Component\Security\Core\User\UserInterface;

class IncludesController extends Controller
{
    private $Gkey = 'AIzaSyC14ed967GfZtOwI8D98w7v0-3yjdpQx9M';
    /**
     *
     */
    public function showSidebar(UserInterface $user, TwitchApiService $twitch_api)
    {
        $login = $user->getTwitchLogin();

        if($user_id = $twitch_api->getUserIdFromLogin($login))
        {
            $follows_id = $twitch_api->getUserFollowsId($user_id);
            $follows = $twitch_api->getUsersFromId($follows_id);

            $live_streams = ($twitch_api->getLiveStreams($follows_id))->data;
            $live_streams_user_id = [];

            foreach($live_streams as $stream)
            {
                $live_streams_user_id[] = $stream->user_id;
            }
        }
        else
        {
            $live_streams = [];
            $follows = [];
            $live_streams_user_id = [];
        }

        // Affichage Subs youtube

        $id = $user->getYoutubeLogin();
        if ($id){
                $urlSub = "https://www.googleapis.com/youtube/v3/subscriptions?part=snippet&channelId=$id&&maxResults=50&key=$this->Gkey";

            try {
                $jsonSub = file_get_contents($urlSub);
                $objSub = json_decode($jsonSub);
                $subs = $objSub->items;
            } catch (\Exception $e) {
                $subs = [];

            }
            if (empty($subs)) {
                $text = 'c\'est vide';
            } else {
                $text = 'ya un truc';
            }
        }else
        {
            $subs = [];
        }

        return $this->render('includes/nav.html.twig', [
            'follows' => $follows,
            'live_streams' => $live_streams,
            'streams_user_id' => $live_streams_user_id,
            'subs'=>$subs
        ]);
    }
}
