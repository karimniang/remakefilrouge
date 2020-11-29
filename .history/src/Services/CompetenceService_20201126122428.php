<?php

namespace App\Services;

use App\Entity\Competence;
use App\Entity\NiveauEvaluation;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\NiveauEvaluationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;



class CompetenceService {
    private $manager;
    private $serializer;
    private $repoGroupeCom;
    private $repoCompetence;
    private $repoNiveau;

    public function __construct(EntityManagerInterface $manager, NiveauEvaluationRepository $repoNiveau,CompetenceRepository $repoCompetence, SerializerInterface $serializer, GroupeCompetenceRepository $repoGroupeCom)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->repoGroupeCom = $repoGroupeCom;
        $this->repoCompetence = $repoCompetence;
        $this->repoNiveau = $repoNiveau;
    }

    public function addCompetence($request){
        $data = json_decode( $request->getContent(),true);
        //dd($data);

        $groupeCompetence = $data["groupeCompetences"][0]["libelle"];
        if (empty($groupeCompetence)) {
            return new JsonResponse("Veuillez rattacher la compétence à un groupe de competences.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $grpComp = $this->repoGroupeCom->findBy(array('libelle' => $groupeCompetence));
        if (empty($grpComp)) {
            return new JsonResponse("Ce groupe de competence n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $niveaux = $data["niveaux"];
        if (count($niveaux) != 3) {
            return new JsonResponse("Une compétence requiert trois niveaux.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $competence = new Competence();
        $competence->addGroupeCompetence($grpComp[0]);
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

        //$this->manager->persist($competence);
        //$this->manager->flush();
        return new JsonResponse("Competence ajoutée", Response::HTTP_CREATED, [], true);
    
    }

    public function updateCompetence($request, $id){
        
        $data = json_decode($request->getContent(), true);
        $competence = $this->repoCompetence->find($id);
        //dd($competence->getGroupeCompetences()[0]);
        if(is_null($competence)) {
            return new JsonResponse("Cette compétence n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        /**Archivage */
        if(isset($data['deleted']) && $data['deleted']) {
            $competence->setDeleted(true);
            $this->manager->flush();
            return new JsonResponse('Compétence archivé.', Response::HTTP_NO_CONTENT, [], true);
        }

        if (isset($data['libelle']) && $data['libelle']) {
            $competence->setLibelle($data['libelle']);
        }
        

        if (isset($$data['niveaux']) && count($$data['niveaux']) != 3) {
            return new JsonResponse("Trois niveaux d'évaluation sont requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $newLibelle =[];
        foreach ($competence->getGroupeCompetences() as $value) {
            for ($i=0; $i < count($data["groupeCompetences"]); $i++) { 
                if ($data["groupeCompetences"][$i]['libelle'] != $value->getLibelle()) {
                    $competence->removeGroupeCompetence($value);
                    $newLibelle[] =$data["groupeCompetences"][$i]['libelle'];
                }
            }
        }
        $newGroupe = array_unique($newLibelle);
        for ($i = 0; $i < count($newGroupe); $i++) {
            $grpComp = $this->repoGroupeCom->findBy(array('libelle' => $newGroupe[$i]));
            if ($grpComp) {
                $competence->addGroupeCompetence($grpComp[0]);
            }else {
                return new JsonResponse("Le groupe de compétence ¦¦".$newGroupe[$i]."¦¦ n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
            }
        }
        if (count($competence->getGroupeCompetences()) < 1) {
            return new JsonResponse("Veuillez renseigner au moins un groupe de compétences existant.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $tabLibelle = [];
        if (isset($data['niveaux']) && !empty($data['niveaux'])) {
            foreach ($competence->getNiveaux() as $value) {
                $competence->removeNiveau($value);
            }
            foreach ($data['niveaux'] as $value) {
                if (!empty($value['libelle']) && !empty($value["groupeAction"]) && !empty($value["critereEvaluation"])) {
                    $niveau = $this->repoNiveau->findBy(array('libelle' => $value['libelle']));
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
        }
       
        if (count($competence->getNiveaux()) != 3) {
            return new JsonResponse("Le libellé, le groupe d'action et le critère d'évaluation d'un niveau sont requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        //$this->manager->persist($competence);
        //$this->manager->flush();
        return new JsonResponse("Competence updating successfully", Response::HTTP_CREATED, [], true);
    }
}