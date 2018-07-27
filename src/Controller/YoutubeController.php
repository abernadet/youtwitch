<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class YoutubeController extends Controller
{

    /**
     * @Route("youtube/search/", name="YsearchAccueil")
     */
    public function index()
    {
        return $this->render('youtube/search.html.twig');
    }

    /**
     * @Route("/youtube/searchsubs/{id}", name="YsearchSubs")
     */
    public function getUrSubs($id)
    {
        $Gkey = 'AIzaSyC14ed967GfZtOwI8D98w7v0-3yjdpQx9M';
        $url = "https://www.googleapis.com/youtube/v3/subscriptions?part=snippet&channelId=$id&key=$Gkey";

        try {
            $json = file_get_contents($url);
            $obj = json_decode($json);
            $subs = $obj->items;
        } catch (\Exception $e) {
            $subs = [];

        }
        if (empty($subs)){
            $text='c\'est vide';
        }else{
            $text = 'ya un truc';
        }
        return $this->render('youtube/searchsubs.html.twig', [
            'subs' => $subs, 'url' => $url,'text'=>$text
        ]);
    }



    /**
     * @Route("/youtube/search/{terme}", name="Ysearch")
     */
    public function search($terme)
    {
        $Gkey = 'AIzaSyC14ed967GfZtOwI8D98w7v0-3yjdpQx9M';
        $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=10&q=$terme&type=channel&key=$Gkey";

        try {
            $json = file_get_contents($url);
            $obj = json_decode($json);
            $results = $obj->items;
        } catch (\Exception $e) {
            $results = [];

        }
        if (empty($results)){
            $text='c\'est vide';
        }else{
            $text = 'ya un truc';
        }
        return $this->render('youtube/searchresult.html.twig', [
            'results' => $results, 'url' => $url,'text'=>$text
        ]);
    }

    /**
     * @Route("/youtube/channel/{channelId}", name="OwnerChan", defaults={"channelId"=1})
     */
    public function DetailChan ($channelId){
        $Gkey = 'AIzaSyC14ed967GfZtOwI8D98w7v0-3yjdpQx9M';
        $url = "https://www.googleapis.com/youtube/v3/channels?part=snippet%2Cstatistics%2CcontentDetails&id=$channelId&key=$Gkey";
        try {
            $json = file_get_contents($url);
            $obj = json_decode($json);

            $details = $obj->items;
            $idUpload = $obj->items[0]->contentDetails->relatedPlaylists->uploads;
            dump($idUpload);
        } catch (\Exception $e) {
            $details = [];
        }


        if (empty($details)){
            $text='c\'est vide';
        }else{
            $text = 'ya un truc';
        }
        return $this->render('youtube/detailschan.html.twig', [
            'details' => $details, 'url' => $url,'text'=>$text,
        ]);
    }



        ## A FAIRE : Une fois la personne retrouvé avec : youtube.channels.list // part = snippet,contentDetails,statistics // ex id : UCus9EeXDcLaCJhVXYd6PJcg
        ## récupérer l'id de la playlist upload :   "contentDetails": {
        ##"relatedPlaylists": {
        ##"uploads": "UUus9EeXDcLaCJhVXYd6PJcg", <--- id de l'ensemble des vidéos upload
        ##"watchHistory": "HL",
        ##"watchLater": "WL"

        ## Ensuite on récupère les vidéos de la playliste upload  dans la requete suivante :PlaylistItems: list
        ##playlistId : UUus9EeXDcLaCJhVXYd6PJcg
        ##maxResults :25
        ##part : snippet,contentDetails



    }


