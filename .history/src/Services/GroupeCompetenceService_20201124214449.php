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



class GroupeCompetenceService {
    private $manager;
    private $serializer;
    private $repoCompetence;

    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer, CompetenceRepository $repoCompetence)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->repoCompetence =$repoCompetence;
    }

    Public function addGroupeCompetence($request){
        $data = json_decode( $request->getContent(),true);
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
            if (!empty($value['libelle'])) {
                $competence = $this->repoCompetence->findBy(array('libelle' => $value["libelle"]));
                if ($competence) {
                    $groupeCompetence->addCompetence($competence[0]);
                } else {
                    if (!in_array($value["libelle"], $tabLibelle)) {
                        $tabLibelle[] = $value["libelle"];
                        $competence = new Competence();
                        $competence->setLibelle($value["libelle"]);
                        $groupeCompetence->addCompetence($competence);
                    }
                }
            }
        }

        if (count($groupeCompetence->getCompetences())<1) {
            return new JsonResponse("Une compétence est requise pour créer un groupe de compétence.", Response::HTTP_BAD_REQUEST, [], true);
        }
        //dd($groupeCompetence);
        //$this->manager->persist($groupeCompetence);
        //$this->manager->flush();
        return new JsonResponse("Groupe Competence added successfulled", Response::HTTP_CREATED, [], true);
    }

    public function updateGroupeCompetence(){
        /*
        $data = json_decode($request->getContent(), true);
        $competence = $repoComp->find($id);
        if(is_null($competence)) {
            return new JsonResponse("Cette compétence n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        /**Archivage */
        if(isset($data['deleted']) && $data['deleted']) {
            $competence->setDeleted(true);
            $em->flush();
            return new JsonResponse('Compétence archivé.', Response::HTTP_NO_CONTENT, [], true);
        }

        if (empty($data['libelle'])) {
            return new JsonResponse('Le libelle est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        $niveaux = $data['niveaux'];
        if (count($niveaux) != 3) {
            return new JsonResponse("Trois niveaux d'évaluation sont requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        
        $competence->setLibelle($data['libelle']);

        foreach ($competence->getGroupeCompetences() as $value) {
            $competence->removeGroupeCompetence($value);
        }

        for ($i = 0; $i < count($data["groupeCompetences"]); $i++) {
            $grpComp = $repoGroupeComp->findBy(array('libelle' => $data["groupeCompetences"][$i]["libelle"]));
            if (!is_null($grpComp)) {
                $competence->addGroupeCompetence($grpComp[0]);
            }
        }

        if (count($competence->getGroupeCompetences()) < 1) {
            return new JsonResponse("Veuillez renseigner au moins un groupe de compétences existant.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $tabLibelle = [];
        foreach ($competence->getNiveaux() as $value) {
            $competence->removeNiveau($value);
        }

        foreach ($data['niveaux'] as $value) {
            if (!empty($value['libelle']) && !empty($value["groupeAction"]) && !empty($value["critereEvaluation"])) {
                $niveau = $repoNiveau->findBy(array('libelle' => $value['libelle']));
                if ($niveau) {
                    $competence->addNiveau($niveau[0]);
                } else {
                    if (!in_array($value['libelle'], $tabLibelle)) {
                        $tabLibelle[] = $value['libelle'];
                        $niveau = new NiveauEvaluation();
                        $niveau->setLibelle($value['libelle']);
                        $niveau->setGroupeAction($value["groupeAction"]);
                        $niveau->setCritereEvaluation($value["critereEvaluation"]);
                        $competence->addNiveau($niveau);
                    }
                }
            }
        }

        if (count($competence->getNiveaux()) < 1) {
            return new JsonResponse("Le libellé, le groupe d'action et le critère d'évaluation d'un niveau sont requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($competence);
        $em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
        */
    }
}