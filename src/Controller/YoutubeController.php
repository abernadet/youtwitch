<?php

namespace App\Controller;


use App\Entity\TrendingSearch;
use App\Entity\User;
use http\Env\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;


class YoutubeController extends Controller
{
    private $Gkey = 'AIzaSyC14ed967GfZtOwI8D98w7v0-3yjdpQx9M';
    /**
     * @Route("/user/youtube/search", name="YsearchAccueil")
     */
    public function index()
    {

        return $this->render('youtube/search.html.twig');
    }

    /**
     * @Route("/user/youtube/searchCount/{number}", name="YsearchCount")
     */
    public function searchCount($number)
    {
        $url = "https://www.googleapis.com/youtube/v3/channels?part=snippet%2Cstatistics%2CcontentDetails&id=$number&key=$this->Gkey";
        try {

            $json = file_get_contents($url);
            $obj = json_decode($json);
            $name = $obj->items[0]->snippet->localized->title;
            $repository = $this->getDoctrine()->getRepository(TrendingSearch::class);
            $utilisateur = $repository->findOneBy(['id_youtube' => $number]);
            dump($utilisateur);
            //$utilisateurId = $utilisateur->getIdYoutube();
            /*if ($utilisateurId != $number){
                $trendingSearch= new TrendingSearch();
                $trendingSearch->setIdYoutube($number);
                $trendingSearch->setName($name);
                $trendingSearch->setNumberSearch(1);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($trendingSearch);
                $entityManager->flush();
            }else{
                /*$numberSearch = $owner->getNumberSearch();
                //dump($trendingSearch);
                $numberSearch=$numberSearch+1;
                $owner->setNumberSearch($numberSearch);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($owner);
                $entityManager->flush();*/
            //}

        } catch (\Exception $e) {
            $name = [];
        }
        return $this->render('youtube/searchCount.html.twig',[
            'name'=>$name,

        ]);
    }


    /**
     *
     */
    public function getUrSubs(UserInterface $user)
    {

        $id = $user->getYoutubeLogin();
        $urlSub = "https://www.googleapis.com/youtube/v3/subscriptions?part=snippet&channelId=$id&maxResults=50&key=$this->Gkey";
        //dump($urlSub);
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
        return $this->render('includes/nav.html.twig', [
            'subs'=>$subs,
            'urlSub'=>$urlSub,
            'text'=>$text
        ]);
    }



    /**
     * @Route("/user/youtube/searchBar/{terme}", name="YsearchBar")
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
     * @Route("/user/youtube/channel/{channelId}", name="OwnerChan", defaults={"channelId"=1})
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
     * @Route("/user/video_player/youtube/{idVideo}", name="YVod", defaults={"idVideo"=0})
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


