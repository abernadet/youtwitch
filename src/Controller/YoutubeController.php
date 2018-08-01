<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class YoutubeController extends Controller
{
    private $Gkey = 'AIzaSyC14ed967GfZtOwI8D98w7v0-3yjdpQx9M';
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

        $url = "https://www.googleapis.com/youtube/v3/subscriptions?part=snippet&channelId=$id&key=$this->Gkey";

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
     * @Route("/youtube/searchBar/{terme}", name="YsearchBar")
     */
    public function searchBar($terme)
    {

        $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=5&q=$terme&type=channel&key=$this->Gkey";

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

    /*
     * @Route("/youtube/search/{terme}", name="Ysearch")
     */
    /* public function search($terme)
     {

         $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=5&q=$terme&type=channel&key=$this->Gkey";

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
     }*/

    /**
     * @Route("/youtube/channel/{channelId}", name="OwnerChan", defaults={"channelId"=1})
     */
    public function DetailChan ($channelId){
        $url = "https://www.googleapis.com/youtube/v3/channels?part=snippet%2Cstatistics%2CcontentDetails&id=$channelId&key=$this->Gkey";
        try {
            $json = file_get_contents($url);
            $obj = json_decode($json);

            $details = $obj->items;
            $idUpload = $obj->items[0]->contentDetails->relatedPlaylists->uploads;

        } catch (\Exception $e) {
            $details = [];
            $idUpload=[];
        }

        $url2 = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=24&playlistId=$idUpload&key=$this->Gkey";
        try {
            $json2 = file_get_contents($url2);
            $obj2 = json_decode($json2);

            $details2 = $obj2->items;
            foreach($details2 as $detail2){
                $idVideos[] = $detail2->snippet->resourceId->videoId;
            }
            //dump($idVideos);
            //$idVideos = $obj2->items[0]->snippet->resourceId->videoId;


            //dump($idVideos);
        } catch (\Exception $e) {
            $details2 = [];
            $idVideos[] = [];
        }
        foreach ($idVideos as $idVideo) {
            $urls3[] = "https://www.googleapis.com/youtube/v3/videos?part=snippet%2C+statistics&id=$idVideo&key=$this->Gkey";
        }

        try {
            foreach ($urls3 as $url3) {
                $json3 = file_get_contents($url3);
                $obj3 = json_decode($json3);

                $details3[] = $obj3->items;
                // dump($details3);
            }
        } catch (\Exception $e) {
            $details3 = [];

        }
        if (empty($details3)){
            $text='c\'est vide';
        }else{
            $text = 'ya un truc';
        }
        return $this->render('youtube/detailschan.html.twig', [
            'details' => $details, 'url' => $url,'text'=>$text, 'details2'=>$details2,'channelId'=>$channelId,'details3'=>$details3
        ]);
    }

    /**
     * @Route("/video_player/youtube/{idVideo}", name="YVod", defaults={"idVideo"=0})
     */
    public function YShowVod($idVideo){
        $url= "https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cstatistics&id=$idVideo&key=$this->Gkey";

        try {
            $json = file_get_contents($url);
            $obj = json_decode($json);

            $infoVideos = $obj->items;


        } catch (\Exception $e) {
            $infoVideos = [];
        }
        if (empty($infoVideos)){
            $text='c\'est vide';
        }else{
            $text = 'ya un truc';
        }
        return $this->render('video_player/youtube_video.html.twig', [
            'infoVideos' => $infoVideos, 'url3' => $url,'text'=>$text
        ]);
    }
}


