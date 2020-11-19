<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="add_user", methods ="POST")
     */
    public function addUser()
    {
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
        
    }
}
