<?php

namespace App\Services;

use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\ReferentielRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ReferentielService 
{
    private $repoGroupeComp;
    private $validator;
    private $manager;
    private $serializer;
    private $userService;
    private $repoReferentiel;

    public function __construct(AddUser $userService, ReferentielRepository $repoReferentiel, GroupeCompetenceRepository $repoGroupeComp, ValidatorInterface $validator, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->repoGroupeComp = $repoGroupeComp;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->userService = $userService;
        $this->repoReferentiel = $repoReferentiel;
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
        //dd($referentiel);


        $this->manager->persist($referentiel);
        $this->manager->flush();
        
        return new JsonResponse("Referentiel added succesfully", Response::HTTP_CREATED, [], true);
    
      
    }

    public function showReferentiel($id_referentiel, $id_groupe){
        
        $referentiel = $this->repoReferentiel->find($id_referentiel);
        if(is_null($referentiel)) {
            return new JsonResponse("Ce référentiel n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $groupeCompetence = $this->repoGroupeComp->find($id_groupe);        
        if(is_null($groupeCompetence)) {
            return new JsonResponse("Ce groupe de compétences n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        foreach ($referentiel->getGroupeCompetences() as  $value) {
            if ($value == $groupeCompetence) {
                $referentielJson = $this->serializer->serialize($referentiel, 'json',["groups"=>["referentiel:read_all"]]);
                return new JsonResponse($referentielJson, Response::HTTP_OK, [], true);
            }else{
                return new JsonResponse("Ce groupe de compétence n'est pas lié à ce référentiel.", Response::HTTP_BAD_REQUEST, [], true);
            }
        }        
    }

    public function updateReferentiel($request, $id){
        
        $data = $request->request->all();
        
        $referentiel = $this->repoReferentiel->find($id);
        if(is_null($referentiel)) {
            return new JsonResponse("Ce référentiel n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }
        
        //Archivage
        if(isset($data['deleted']) && $data['deleted']) {
            $referentiel->setDeleted(true);
            $this->manager->flush();
            return new JsonResponse('Référentiel archivé.', Response::HTTP_NO_CONTENT, [], true);
        }
        dd($referentiel);

        // Modification 
        foreach ($data as $key => $value) {
            if (isset($key) || !empty($key)) {
                if (($key != "_method") || ($key != "programme") || ($key != "groupeCompetences")) {
                    $toSet = "set".ucfirst(strtolower($key));
                    $referentiel->$toSet($value);
                }
            }
        }

/*
        if (empty($data['libelle'])) {
            return new JsonResponse('Le libelle est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }
        if (empty($data['description'])) {
            return new JsonResponse('La présentation est requise.', Response::HTTP_BAD_REQUEST, [], true);
        }
        if (empty($data['critereAdmissions'])) {
            return new JsonResponse("Un critère d'admission est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        if (empty($data['critereEvaluations'])) {
            return new JsonResponse("Un critère d'évaluation est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        if (empty($data['groupeCompetences'])) {
            return new JsonResponse("Un groupe de compétences est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }


        $tabCritereAdmission = $referentiel->getCritereAdmissions();
        foreach ($tabCritereAdmission as $value) {
            $referentiel->removeCritereAdmission($value);
        }

        $tabCritereEvaluation = $referentiel->getCritereEvaluations();
        foreach ($tabCritereEvaluation as $value) {
            $referentiel->removeCritereEvaluation($value);
        }

        $tabGroupeCompetence = $referentiel->getGroupeCompetences();
        foreach ($tabGroupeCompetence as $value) {
            $referentiel->removeGroupeCompetence($value);
        }

        //Partie ajout de la modification
        $tabLibelle = [];
        foreach ($data['critereAdmissions'] as $value) {
            if ($value != "") {
                $critereAdmission = $repoAdmisssion->findBy(array('libelle' => $value));
                if ($critereAdmission) {
                    $referentiel->addCritereAdmission($critereAdmission[0]);
                } else {
                    if (!in_array($value, $tabLibelle)) {
                        $tabLibelle[] = $value;
                        $critereAdmission = new CritereAdmission();
                        $critereAdmission->setLibelle($value);
                        $referentiel->addCritereAdmission($critereAdmission);
                    }
                }
            }
        }
        if (count($referentiel->getCritereAdmissions()) < 1) {
            return new JsonResponse("Le libelle du critère d'admission est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $tabLibelle = [];
        foreach ($data['critereEvaluations'] as $value) {
            if ($value != "") {
                $critereEvaluation = $repoEvaluation->findBy(array('libelle' => $value));
                if ($critereEvaluation) {
                    $referentiel->addCritereEvaluation($critereEvaluation[0]);
                } else {
                    if (!in_array($value, $tabLibelle)) {
                        $tabLibelle[] = $value;
                        $critereEvaluation = new CritereEvaluation();
                        $critereEvaluation->setLibelle($value);
                        $referentiel->addCritereEvaluation($critereEvaluation);
                    }
                }
            }
        }
        if (count($referentiel->getCritereEvaluations()) < 1) {
            return new JsonResponse("Le libelle du critère d'évaluation est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        foreach ($data['groupeCompetences'] as $value) {
            if ($value != "") {
                $groupeCompetence = $repoGroupeComp->findBy(array('libelle' => $value));
                if (!empty($groupeCompetence)) {
                    $referentiel->addGroupeCompetence($groupeCompetence[0]);
                }
            }
        }
        if (count($referentiel->getGroupeCompetences()) < 1) {
            return new JsonResponse("Un groupe de compétence existant est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $file = $request->files;
        if (is_null($file->get('programme'))) {
            return new JsonResponse("Le programme est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $fileType = explode("/", $file->get('programme')->getMimeType())[1];
        $filePath = $file->get('programme')->getRealPath();

        $programme = file_get_contents($filePath, 'pdf/pdf.' . $fileType);
        $referentiel->setProgramme($programme);
        $referentiel->setLibelle($data["libelle"]);
        $referentiel->setDescription($data["description"]);

        $em->persist($referentiel);
        $em->flush();
        return new JsonResponse("success", Response::HTTP_OK, [], true);
         */
    }
}