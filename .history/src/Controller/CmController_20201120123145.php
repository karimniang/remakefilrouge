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

    public function accesscontrol(){
        if (!in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return new JsonResponse('Vous n\'avez pas accès à cette ressource.', Response::HTTP_FORBIDDEN, [], true);
        }
    }

    
}
