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
        //return new JsonResponse($userTab['lastname']);
        //dd($userTab);
        $username = strtolower(substr($userTab['lastname'],0,3)."123".explode('@',$userTab['email'])[0].substr($userTab['firstname'],0,3));
        //dd($username);
        if($this->repoUser->findBy(["username"=>$username])){
            return new JsonResponse("This username already existe", Response::HTTP_BAD_REQUEST, [], true);
        }
        $avatar = $request->files->get('avatar');
       
        
        $profil = $this->repoProfils->findBy(["libelle"=>strtolower(explode("\\", $entity)[2])]);
        //dd($profil);
        unset($userTab['profil']);
        $user = $this->serializer->denormalize($userTab, $entity, true,["groups" => "user:write"]);
        $user->setProfil($profil[0]);
        $user->setPassword($this->encoder->encodePassword($user, 'pass_1234'));
        $user->setUsername($username);
        
        if (!is_null($avatar)) {
            $user->setAvatar($this->uploadFile($avatar, "imageProfil"));
        }


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

    public function updateInfoUser($request,$id)
    {
        
        $data = $request->request->all();
        return new JsonResponse("Success Updating", Response::HTTP_OK, [], true);

       
        //$aprenant = $repoApre->findOneByIdUser($user->getId());
        $user = $this->repoUser->find($id);
        //dd($data);
        if ($user == null){
            $reponseJon = $this->serializer->serialize(["msg"=>"Cet utilisateur n'existe pas"], 'json');
            return new JsonResponse($reponseJon, Response::HTTP_OK, [], true);
        }
        $avatar = $request->files->get('avatar');
        if (!is_null($avatar)) {
            $user->setAvatar($this->uploadFile($avatar, "imageProfil"));
        }

        /*if (count($data)<1){
            $reponseJon = $this->serializer->serialize(["response"=>"les donnes sont aubligatoire"],'json');
            return new JsonResponse($reponseJon, Response::HTTP_OK, [], true);
        }*/
        foreach ($data as $key => $value) {
            if (isset($key) || !empty($key)) {
                if ($key != "_method" && $key != "id" ) {
                    $toSet = "set".ucfirst(strtolower($key));
                    $user->$toSet($value);
                }
            }
        }
        
        
        //dd($user);
        $this->manager->flush();
        $reponseJon = $this->serializer->serialize(["response"=>"Success Updating"],'json');
        return new JsonResponse("Success Updating", Response::HTTP_OK, [], true);
    }
}