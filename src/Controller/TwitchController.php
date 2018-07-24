<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TwitchController extends Controller
{
    /**
     * @Route("/testtwitch/{name}", name="testtwitch")
     */

    public function getFollows($name)
    {
        dump($name);
        $url = 'https://api.twitch.tv/kraken/users/'.$name.'/follows/channels?client_id=wb57fz1kqexwbl5w03vrig184qh78h';

       try{
            $json = file_get_contents($url);
            $obj = json_decode($json);
            $follows = $obj->follows;
        }catch(\Exception $e){
            $follows = [];
        }

        return $this->render('twitch/index.html.twig', [
            'follows' => $follows,
        ]);
    }
}
