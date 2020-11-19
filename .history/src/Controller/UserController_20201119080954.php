<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserProfilRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="add_user", methods ="POST")
     */
    public function addUser(Request $request, UserProfilRepository $repoProfils)
    {
        if (!in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return new JsonResponse('Vous n\'avez pas accès à cette ressource.', Response::HTTP_FORBIDDEN, [], true);
        }
        $user = new User();
        $userTab = $request->request->all();
        if (empty($userTab['profil'])){
            return new JsonResponse("Le profil est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $profil = $repoProfils->find(explode("/",$userTab['profil'])[1]);
        dd($profil);
    
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
        
    }
}
