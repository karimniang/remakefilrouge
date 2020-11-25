<?php

namespace App\Controller;

use App\Services\CompetenceService;
use App\Services\GroupeCompetenceService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
            dd()
        }
        return $groupeCompetenceService->addGroupeCompetence($request);
    }
}
