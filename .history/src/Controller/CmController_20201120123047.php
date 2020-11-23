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
     * @Route("/admin/admins", name="add_admin", methods ="POST")
     */
    public function addAdmin(AddUser $addUser,Request $request)
    {
        $user = $this->getUser();
        return $addUser->addUser("App\Entity\Admin", $request, $user);
    }

    /**
     * @Route("/admin/formateurs", name="add_formateur", methods ="POST")
     */
    public function addFormateur(AddUser $addUser, Request $request)
    {
        $user = $this->getUser();
        return $addUser->addUser("App\Entity\Formateur", $request,$user);
    }

    /**
     * @Route("/admin/apprenants", name="add_apprenant", methods ="POST")
     */
    public function addApprenant(AddUser $addUser,Request $request)
    {
        $user = $this->getUser();
        return $addUser->addUser("App\Entity\Apprenant", $request, $user);
    }

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
