<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class YoutubeController extends Controller
{

    /**
     * @Route("/youtube/search/", name="Ysearch")
     */
    public function search()
    {
        $url = "https://www.googleapis.com/youtube/v3/subscriptions?part=snippet&channelId=UCCpKmtXcI-ZkKxBP7KnvX4w&key=AIzaSyC14ed967GfZtOwI8D98w7v0-3yjdpQx9M";
        try {
            $json = file_get_contents($url);
            $obj = json_decode($json);
            $subs = $obj->items;
        }catch (\Exception $e){
            $subs=[];
        }
        return $this->render('youtube/search.html.twig', [
            'subs' => $subs,
        ]);
    }

}
