<?php

namespace App\Controller;

use App\Services\AddUser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/api")
 */
class ApprenantController extends AbstractController
{


    /**
     * @Route("/admin/apprenants", name="add_apprenant", methods ="POST")
     */
    public function addApprenant(AddUser $addUser,Request $request)
    {
        $user = $this->getUser();
        return $addUser->addUser("App\Entity\Apprenant", $request, $user);
    }
    
    
}
