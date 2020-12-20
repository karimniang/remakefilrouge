<?php

namespace App\Controller;

use App\Services\ReferentielService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 */

class UserController extends AbstractController
{
    /**
     * @Route(name="get_user", path="/admin/user/connected", methods={"GET"})
     */

    public function getUser(){
        
        dd("ok");
    }
}
