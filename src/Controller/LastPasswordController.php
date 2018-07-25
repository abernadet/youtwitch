<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LastPasswordController extends Controller
{
    /**
     * @Route("/last/password", name="last_password")
     */
    public function index()
    {
        return $this->render('last_password/index.html.twig', [
            'controller_name' => 'LastPasswordController',
        ]);
    }

    /**
     * @Route("/reset", name="reset")
     *
     */
    public function restPassword(Request $request){


    }
}
