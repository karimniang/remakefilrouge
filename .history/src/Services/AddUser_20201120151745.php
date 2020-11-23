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

    public function addUser($entity, $request, $user)
    {
        if (!in_array("ROLE_ADMIN", $user->getRoles())) {
            return new JsonResponse('Vous n\'avez pas accès à cette ressource.', Response::HTTP_FORBIDDEN, [], true);
        }

        $userTab = $request->request->all();
        if($this->repoUser->findBy(["username"=>$userTab["lastname"] . $userTab["firstname"]])){
            return new JsonResponse("This username already existe", Response::HTTP_BAD_REQUEST, [], true);
        }
        $avatar = $request->files->get('avatar');
        if (is_null($avatar)) {
            return new JsonResponse("L'avatar est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        
        $profil = $this->repoProfils->findBy(["libelle"=>strtolower(explode("\\", $entity)[2])]);
        //dd($profil);
        unset($userTab['profil']);
        $user = $this->serializer->denormalize($userTab, $entity, true);
        $user->setAvatar($this->uploadFile($avatar, "imageProfil"));
        $user->setProfil($profil[0]);
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

    public function updateInfoUser($request,$id,$entity)
    {
        //dd($user);
        //$aprenant = $repoApre->findOneByIdUser($user->getId());
        $user = $this->repoUser->find($id);
        //dd($user);
        if ($user == null){
            $reponseJon = $this->serializer->serialize(["msg"=>"Cet utilisateur n'existe pas"], 'json');
            return new JsonResponse($reponseJon, Response::HTTP_OK, [], true);
        }
        $data = $request->request->all();
        /*if (count($data)<1){
            $reponseJon = $this->serializer->serialize(["response"=>"les donnes sont aubligatoire"],'json');
            return new JsonResponse($reponseJon, Response::HTTP_OK, [], true);
        }*/
        $user->setUsername($data['username']);
        $user->setPassword($this->encoder->encodePassword($entity,$data['password']));
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        //$aprenant->setAttente(false);
        dd($user);
        //$this->manager->flush();
        $reponseJon = $this->serializer->serialize(["response"=>"succes"],'json');
        return new JsonResponse($reponseJon, Response::HTTP_OK, [], true);
    }
}