<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/home", name="home")
     */
    public function home()
    {

        return $this->render('index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/home/search", name="search")
     */
    public function listSearch($service)
    {
        searchListByKeyword($service,
            'snippet',
            array('maxResults' => 25, 'q' => 'squezzie', 'type' => 'video'));


        return $this->render('home/search.html.twig');
    }

    function searchListByKeyword($service, $part, $params) {
        $params = array_filter($params);
        $response = $service->search->listSearch(
            $part,
            $params
        );

        print_r($response);
    }

}
