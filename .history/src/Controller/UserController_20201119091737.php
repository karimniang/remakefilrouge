<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="add_user", methods ="POST")
     */
    public function addUser(Request $request, UserProfilRepository $repoProfils,EntityManagerInterface $manager, SerializerInterface $serializer, UserPasswordEncoderInterface $encoder)
    {
        if (!in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return new JsonResponse('Vous n\'avez pas accès à cette ressource.', Response::HTTP_FORBIDDEN, [], true);
        }
        $user = new User();
        $userTab = $request->request->all();
        if (empty($userTab['profil'])){
            return new JsonResponse("Le profil est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $avatar = $request->files->get('avatar');
        if (is_null($avatar)) {
            return new JsonResponse("L'avatar est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $profil = $repoProfils->find(explode("/",$userTab['profil'])[1]);
        unset($userTab['profil']);
        $user = $serializer->denormalize($userTab, User::class, true);
        $user->setAvatar($this->uploadFile($avatar,"imageProfil"));
        $user->setProfil($profil);
        $user->setPassword($encoder->encodePassword($user, 'pass_1234'));
        $user->setUsername($userTab["lastname"].$userTab["firstname"]);
        
        //dd($user);
        $manager->persist($user);
        $manager->flush();
    
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
        
    }

    /**Fonction traitement image */
    public function uploadFile($file, $name)
    {
        $fileType = explode("/", $file->getMimeType())[1];
        $filePath = $file->getRealPath();
        return file_get_contents($filePath, $name . '.' . $fileType);
    }   
}
