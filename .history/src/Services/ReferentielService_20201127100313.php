<?php

namespace App\Services;

use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ReferentielService 
{
    private $repoGroupeComp;
    private $validator;
    private $manager;
    private $serializer;
    private $userService;

    public function __construct(AddUser $userService, GroupeCompetenceRepository $repoGroupeComp, ValidatorInterface $validator, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->repoGroupeComp = $repoGroupeComp;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->userService = $userService;
    }



    public function addReferentiel($request){
        $data = $request->request->all();
        $referentiel = $this->serializer->denormalize($data, Referentiel::class, true, ["groups" => ["referentiel:write"]]);
        $errors = $this->validator->validate($referentiel);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }

        if (empty($data['groupeCompetences'])) {
            return new JsonResponse("Un groupe de compétences est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        
        foreach ($data['groupeCompetences'] as $value) {
            if ($value != "") {
                $groupeCompetence = $this->repoGroupeComp->findBy(array('libelle' => $value));
                if (!empty($groupeCompetence)) {
                    $referentiel->addGroupeCompetence($groupeCompetence[0]);
                }else{
                    return new JsonResponse("Le groupe de compétence ¨".$value."¨ n'existe pas !!!.", Response::HTTP_BAD_REQUEST, [], true);
                }
            }
        }
        if (count($referentiel->getGroupeCompetences()) < 1) {
            return new JsonResponse("Un groupe de compétence existant est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        

        $file = $request->files->get('programme');
        if (is_null($file)) {
            return new JsonResponse("Le programme est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $referentiel->setProgramme($this->userService->uploadFile($file,"programme"));
        dd($referentiel);

        /*
        $fileType = explode("/", $file->get('programme')->getMimeType())[1];
        $filePath = $file->get('programme')->getRealPath();

        $programme = file_get_contents($filePath, 'programme.'.$fileType);
        $referentiel->setProgramme($programme);


        $em->persist($referentiel);
        $em->flush();
          */
        return new JsonResponse("Referentiel added succesfully", Response::HTTP_CREATED, [], true);
    
      
    }
}