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

    public function __construct(GroupeCompetenceRepository $repoGroupeComp, ValidatorInterface $validator, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->repoGroupeComp = $repoGroupeComp;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->serializer = $serializer;
    }



    public function addReferentiel($request){
        $data = $request->request->all();
        $referentiel = $this->serializer->denormalize($data, Referentiel::class, true, ["groups" => ["referentiel:write"]]);
        $errors = $this->validator->validate($referentiel);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }
        dd($referentiel);
/*
        if (empty($data['critereAdmissions'])) {
            return new JsonResponse("Un critère d'admission est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        if (empty($data['critereEvaluations'])) {
            return new JsonResponse("Un critère d'évaluation est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        if (empty($data['groupeCompetences'])) {
            return new JsonResponse("Un groupe de compétences est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $tabLibelle = [];
        foreach ($data['critereAdmissions'] as $value) {
            if ($value != "" && !in_array($value, $tabLibelle)) {
                $tabLibelle[] = $value;
                $critereAdmission = new CritereAdmission();
                $critereAdmission->setLibelle($value);
                $referentiel->addCritereAdmission($critereAdmission);
            }
        }
        if (count($referentiel->getCritereAdmissions()) < 1) {
            return new JsonResponse("Le libelle du critère d'admission est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $tabLibelle = [];
        foreach ($data['critereEvaluations'] as $value) {
            if ($value != "" && !in_array($value, $tabLibelle)) {
                $tabLibelle[] = $value;
                $critereEvaluation = new CritereEvaluation();
                $critereEvaluation->setLibelle($value);
                $referentiel->addCritereEvaluation($critereEvaluation);
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

        $programme = file_get_contents($filePath, 'programme.'.$fileType);
        $referentiel->setProgramme($programme);


        $em->persist($referentiel);
        $em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    
        */
    }
}