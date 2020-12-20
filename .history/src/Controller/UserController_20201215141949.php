<?php

namespace App\Controller;

use App\Services\ReferentielService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */

class UserController extends AbstractController
{

    private $security;
    private $serializer;

    public function __construct (Security $security, SerializerInterface $serializer)
    {
       $this->security = $security;
       $this->serializer = $serializer;
    }

    /**
     * @Route(name="get_user", path="/admin/user/connected", methods={"GET"})
     */

    public function getUser(){
        
        //dd("ok");
        $user = $this->security->getUser();
        dd($user);
        return new JsonResponse($user);
    }
}
