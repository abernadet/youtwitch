<?php
/**
 * Created by PhpStorm.
 * User: pvignaud
 * Date: 01/08/2018
 * Time: 14:31
 */

namespace App\Service;


use Symfony\Component\Security\Core\User\UserInterface;

class YoutubeService
{
    /*private $Gkey = 'AIzaSyC14ed967GfZtOwI8D98w7v0-3yjdpQx9M';

    public function getUrSubs(UserInterface $user)
    {

        $name = $user->getYoutubeLogin();
        $url = "https://www.googleapis.com/youtube/v3/channels?part=snippet%2CcontentDetails&forUsername=$name&key=$this->Gkey";

        try {
            $json = file_get_contents($url);
            $obj = json_decode($json);
            $ids = $obj->items;

            foreach ($ids as $id){
                $idChannels[] = $id->id;
            }
        } catch (\Exception $e) {
            $idChannels = [];
        }

        foreach ($idChannels as $idChannel) {
            $urlSub = "https://www.googleapis.com/youtube/v3/subscriptions?part=snippet&channelId=$idChannel&key=$this->Gkey";
            //dump($urlSub);
        }
        try {
            $jsonSub = file_get_contents($urlSub);
            $objSub = json_decode($jsonSub);
            $subs = $objSub->items;
        } catch (\Exception $e) {
            $subs = [];

        }
        if (empty($subs)){
            $text='c\'est vide';
        }else{
            $text = 'ya un truc';
        }
        return $subs;

    }*/
}