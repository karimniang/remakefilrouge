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
class AdminController extends AbstractController
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
     * @Route("/admin/admins/{id}", name="put_admin", methods ="PUT")
     */
    public function putAdd(AddUser $addUser,Request $request, )
    {
        $user = $this->getUser();
        return $addUser->updateInfoUser($user);
    }

   

    
}
