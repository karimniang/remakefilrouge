<?php

namespace App\Controller;

use App\Services\ReferentielService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/api")
 */

class UserController extends AbstractController
{

    private $security;

    public function __construct (Security $security)
    {
       $this->security = $security;
    }

    /**
     * @Route(name="get_user", path="/admin/user/connected", methods={"GET"})
     */

    public function getUser(){
        
        //dd("ok");
        dd($this->security->getUser());
    }
}
