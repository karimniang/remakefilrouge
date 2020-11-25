<?php

namespace App\Services;

use App\Entity\Competence;
use App\Entity\NiveauEvaluation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;



class CompetenceService {
    private $manager;
    private $serializer;
    private $repoGroupeCom;

    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer, GroupeCompetenceRepository $repoGroupeCom)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->repoGroupeCom =$repoGroupeCom;
    }

    public function addCompetence($request){
        $data = json_decode( $request->getContent(),true);
        //dd($data);

        $groupeCompetence = $data->getGroupeCompetences()[0]->getLibelle();
        if (empty($groupeCompetence)) {
            return new JsonResponse("Veuillez rattacher la compétence à un groupe de competences.", Response::HTTP_BAD_REQUEST, [], true);
        }
        

        $niveaux = $data["niveaux"];
        if (count($niveaux) != 3) {
            return new JsonResponse("Une compétence requiert trois niveaux.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $competence = new Competence();
        

        /*if (empty($grpComp)) {
            return new JsonResponse("Ce groupe de competence n\'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $competence->addGroupeCompetence($grpComp[0]);
        */
        $competence->setLibelle($data["libelle"]);
        $tabLibelle = [];

        foreach ($niveaux as $value) {
            if (!empty($value["libelle"]) && !empty($value["groupeAction"]) && !empty($value["critereEvaluation"])) {
                if (!in_array($value["libelle"], $tabLibelle)) {
                    $tabLibelle[] = $value["libelle"];
                    $niveau = new NiveauEvaluation();

                    $niveau->setLibelle($value["libelle"]);
                    $niveau->setGroupeAction($value['groupeAction']);
                    $niveau->setCritereEvaluation($value["critereEvaluation"]);

                    $competence->addNiveau($niveau);
                }
            }
        }

        if (count($competence->getNiveaux()) != 3) {
            return new JsonResponse("Une compétence requiert trois niveaux avec leur groupe d'action et leur critere d'evaluation.", Response::HTTP_BAD_REQUEST, [], true);
        }
        //dd($competence);

        $this->manager->persist($competence);
        $this->manager->flush();
        return new JsonResponse("Competence ajoutée", Response::HTTP_CREATED, [], true);
    
    }
}