<?php

namespace App\Services;

use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Entity\NiveauEvaluation;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;



class GroupeCompetenceService
{
    private $manager;
    private $serializer;
    private $repoCompetence;
    private $repoGroupeComp;
    private $updateLinkService;

    public function __construct(UpdateLinkService $updateLinkService,EntityManagerInterface $manager, SerializerInterface $serializer, CompetenceRepository $repoCompetence, GroupeCompetenceRepository $repoGroupeComp)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->repoCompetence = $repoCompetence;
        $this->repoGroupeComp = $repoGroupeComp;
        $this->updateLinkService = $updateLinkService;
    }

    public function addGroupeCompetence($request)
    {
        $data = json_decode($request->getContent(), true);
        //dd($data);
        $competences = $data['competences'];
        if (count($competences) < 1) {
            return new JsonResponse("Une compétence est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $groupeCompetence = new GroupeCompetence();
        $groupeCompetence->setLibelle($data['libelle']);
        $groupeCompetence->setDescription($data['description']);
        $tabLibelle = [];


        foreach ($competences as $value) {
            if (!empty($value)) {
                $competence = $this->repoCompetence->findBy(array('libelle' => $value));
                if ($competence) {
                    $groupeCompetence->addCompetence($competence[0]);
                } else {
                    if (!in_array($value, $tabLibelle)) {
                        $tabLibelle[] = $value;
                        $competence = new Competence();
                        $competence->setLibelle($value);
                        $groupeCompetence->addCompetence($competence);
                    }
                }
            }
        }

        if (count($groupeCompetence->getCompetences()) < 1) {
            return new JsonResponse("Une compétence est requise pour créer un groupe de compétence.", Response::HTTP_BAD_REQUEST, [], true);
        }
        //dd($groupeCompetence);
        $this->manager->persist($groupeCompetence);
        $this->manager->flush();
        return new JsonResponse("Groupe Competence added successfulled", Response::HTTP_CREATED, [], true);
    }

    public function updateGroupeCompetence($request, $id)
    {
         $data = json_decode($request->getContent(), true);

        $groupeCompetence = $this->repoGroupeComp->find($id);
        if (is_null($groupeCompetence)) {
            return new JsonResponse("Ce groupe de compétences n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        if (isset($data['deleted']) && $data['deleted']) {
            $groupeCompetence->setDeleted(true);
            foreach ($groupeCompetence->getCompetences() as $value) {
                $groupeCompetence->removeCompetence($value);
            }
            $this->manager->flush();
            return new JsonResponse('Groupe de Compétences archivé.', Response::HTTP_NO_CONTENT, [], true);
        }

        foreach ($data as $key => $value) {
            if (isset($key) || !empty($key)) {
                if (!is_array($value)) {
                    $toSet = "set" . ucfirst(strtolower($key));
                    $groupeCompetence->$toSet($value);
                }
            }
        }  
        //return new JsonResponse("Groupe Competence added successfulled", Response::HTTP_CREATED, [], true);

        if (count($data['competences']) < 1) {
            return new JsonResponse("Une compétence est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }

        
        foreach ($data['competences'] as $value) {
            $competencesBrutes[] = $value;
        }
        //dd($competencesBrutes);
        $groupeCompetence = $this->updateLinkService->toAdded($competencesBrutes,$groupeCompetence,"competence",$this->repoCompetence);
        
        if (count($groupeCompetence->getCompetences())<1) {
            return new JsonResponse("Les libellés des compétences sont requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        //$this->manager->persist($groupeCompetence);
        //$this->manager->flush();
        $GroupeCompetenceJson = $this->serializer->serialize($groupeCompetence, 'json');
        return new JsonResponse($GroupeCompetenceJson, Response::HTTP_OK, [], true);

        /**Archivage */
    }

}
