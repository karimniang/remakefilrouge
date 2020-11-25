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

        /*$groupeCompetence = $data->getGroupeCompetences()[0]->getLibelle();
        if (empty($groupeCompetence)) {
            return new JsonResponse("Veuillez rattacher la compétence à un groupe de competences.", Response::HTTP_BAD_REQUEST, [], true);
        }*/

        $niveaux = $data->getNiveaux();
        if (count($niveaux) != 3) {
            return new JsonResponse("Une compétence requiert trois niveaux.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $competence = new Competence();
        //$grpComp = $this->repo->findBy(array('libelle' => $groupeCompetence));

        /*if (empty($grpComp)) {
            return new JsonResponse("Ce groupe de competence n\'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $competence->addGroupeCompetence($grpComp[0]);
        */
        $competence->setLibelle($data->getLibelle());
        $tabLibelle = [];

        foreach ($niveaux as $value) {
            if (!empty($value->getLibelle()) && !empty($value->getGroupeAction()) && !empty($value->getCritereEvaluation())) {
                if (!in_array($value->getlibelle(), $tabLibelle)) {
                    $tabLibelle[] = $value->getlibelle();
                    $niveau = new NiveauEvaluation();

                    $niveau->setLibelle($value->getLibelle());
                    $niveau->setGroupeAction($value->getGroupeAction());
                    $niveau->setCritereEvaluation($value->getCritereEvaluation());

                    $competence->addNiveau($niveau);
                }
            }
        }

        //if (count($competence->getNiveaux()) != 3) {
          //  return new JsonResponse("Une compétence requiert trois niveaux avec leur groupe d'action et leur critere d'evaluation.", Response::HTTP_BAD_REQUEST, [], true);
        //}

        //$this->em->persist($competence);
        //$this->em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    
    }
}