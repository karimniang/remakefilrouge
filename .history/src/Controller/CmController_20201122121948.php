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
class CmController extends AbstractController
{
    
    /**
     * @Route("/admin/cms", name="add_cm", methods ="POST")
     */
    public function addCm(AddUser $addUser,Request $request)
    { $user = $this->getUser();

        return $addUser->addUser("App\Entity\CM", $request, $user);
    }

    /**
     * @Route("/admin/apprenants/{id}", name="put_apprenant", methods ="POST")
     */
    public function putAdd(AddUser $addUser,Request $request, $id)
    {
        return $addUser->updateInfoUser("App\Entity\Apprenant", $request, $id);
    }

   

    
}
