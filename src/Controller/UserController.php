<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/connexion", name="user_connexion")
     */
    public function connectUser()
    {
        return $this->render('user/connexion.html.twig', [

        ]);
    }

    /**
     * @Route("/inscription", name="user_inscription")
     */
    public function addUser()
    {
        return $this->render('user/inscription.html.twig', [

        ]);
    }
}
