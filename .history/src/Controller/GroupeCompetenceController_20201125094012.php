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
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse("Vous n'êtes pas autorisé à créer un groupe de compétences.", Response::HTTP_BAD_REQUEST, [], true);
        }
        return $groupeCompetenceService->addGroupeCompetence($request);
    }

     /** 
     * @Route("/admin/grpecompetences/{id}", name="put_groupe_competence", methods ="PUT")
     */
    public function UpdateGroupeCompetence(GroupeCompetenceService $groupeCompetenceService, Request $request){
        
        return $groupeCompetenceService->updateGroupeCompetence($request);
    }
}
