<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\User;
use App\Repository\UserProfilRepository;
use App\Repository\UserRepository;
use App\Services\AddUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class UserController extends AbstractController
{
    private $encoder;
    private $repoProfils;
    private $manager;
    private $serializer;
    private $repoUser;
    private $addUser;

    public function __construct(UserRepository $repoUser,UserProfilRepository $repoProfils, EntityManagerInterface $manager, SerializerInterface $serializer, UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->repoProfils = $repoProfils;
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->repoUser = $repoUser;
    }

    /**
     * @Route(
     *     name="add_admin",
     *     path="/admin/admins",
     *     methods={"POST"},
     *     defaults={
     *         "_api_resource_class"=Admin::class,
     *         "_api_collection_operation_name"="post_admin"
     *     }
     * )
     */
    public function addAdmin(AddUser $addUser,Request $request)
    {
        return $addUser->addUser("App\Entity\Admin", $request);
    }

    /**
     * @Route(
     *     name="add_formateur",
     *     path="/admin/formateurs",
     *     methods={"POST"},
     *     defaults={
     *         "_api_resource_class"=Formateur::class,
     *         "_api_collection_operation_name"="post_formateur"
     *     }
     * )
     */
    public function addFormateur(AddUser $addUser, Request $request)
    {
        return $addUser->addUser("App\Entity\Formateur", $request);
    }

    /**
     * @Route(
     *     name="add_apprenant",
     *     path="/admin/apprenants",
     *     methods={"POST"},
     *     defaults={
     *         "_api_resource_class"=Apprenant::class,
     *         "_api_collection_operation_name"="post_apprenant"
     *     }
     * )
     */
    public function addApprenant(AddUser $addUser,Request $request)
    {
        return $addUser->addUser("App\Entity\Apprenant", $request);
    }

    /**
     * @Route(
     *     name="add_cm",
     *     path="/admin/cms",
     *     methods={"POST"},
     *     defaults={
     *         "_api_resource_class"=CM::class,
     *         "_api_collection_operation_name"="post_cm"
     *     }
     * )
     */
    public function addCm(AddUser $addUser,Request $request)
    {
        return $addUser->addUser("App\Entity\CM", $request);
    }

    public function accesscontrol(){
        if (!in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return new JsonResponse('Vous n\'avez pas accès à cette ressource.', Response::HTTP_FORBIDDEN, [], true);
        }
    }

    
}
