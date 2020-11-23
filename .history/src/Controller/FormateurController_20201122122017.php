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
class FormateurController extends AbstractController
{
    

    /**
     * @Route("/admin/formateurs", name="add_formateur", methods ="POST")
     */
    public function addFormateur(AddUser $addUser, Request $request)
    {
        $user = $this->getUser();
        return $addUser->addUser("App\Entity\Formateur", $request,$user);
    }

    /**
     * @Route("/admin/formateurs/{id}", name="put_apprenant", methods ="POST")
     */
    public function putAdd(AddUser $addUser,Request $request, $id)
    {
        return $addUser->updateInfoUser("App\Entity\Apprenant", $request, $id);
    }

    
}
