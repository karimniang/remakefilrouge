<?php

namespace App\Controller;

use App\Services\GroupeCompetenceService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api")
 */

class GroupeCompetenceController extends AbstractController
{

    /** 
     * @Route("/admin/grpecompetences", name="add_groupe_competence", methods ="POST")
     */
    public function addGroupeCompetence(GroupeCompetenceService $groupeCompetenceService, Request $request){
        if (!$this->isGranted("GROUPE_CREATE")) {
            return new JsonResponse("Vous n'êtes pas autorisé à créer un grope de compétences.", Response::HTTP_BAD_REQUEST, [], true);
        }
        return $groupeCompetenceService->addGroupeCompetence($request);
    }
}
