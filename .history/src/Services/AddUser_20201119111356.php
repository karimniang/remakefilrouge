<?php

namespace App\Services;

use App\Repository\UserRepository;
use App\Repository\UserProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AddUser
{
    private $encoder;
    private $repoProfils;
    private $manager;
    private $serializer;
    private $repoUser;

    public function __construct(UserRepository $repoUser,UserProfilRepository $repoProfils, EntityManagerInterface $manager, SerializerInterface $serializer, UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->repoProfils = $repoProfils;
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->repoUser = $repoUser;
    }

    public function addUser($entity, $request)
    {
        

        $userTab = $request->request->all();
        if (empty($userTab['profil'])) {
            return new JsonResponse("Le profil est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        if($this->repoUser->findBy(["username"=>$userTab["lastname"] . $userTab["firstname"]])){
            return new JsonResponse("This username already existe", Response::HTTP_BAD_REQUEST, [], true);
        }
        $avatar = $request->files->get('avatar');
        if (is_null($avatar)) {
            return new JsonResponse("L'avatar est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $profil = $this->repoProfils->find(explode("'\'", $entity)[2]);
        dd($profil);
        unset($userTab['profil']);
        $user = $this->serializer->denormalize($userTab, $entity, true);
        $user->setAvatar($this->uploadFile($avatar, "imageProfil"));
        $user->setProfil($profil);
        $user->setPassword($this->encoder->encodePassword($user, 'pass_1234'));
        $user->setUsername($userTab["lastname"] . $userTab["firstname"]);


        //dd($user);
        $this->manager->persist($user);
        $this->manager->flush();

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