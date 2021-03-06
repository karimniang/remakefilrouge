<?php

namespace App\Controller;

use App\Services\CompetenceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 */

class GroupeCompetenceController extends AbstractController
{

    /**
     * @Route("/admin/competences", name="add_competence", methods ="POST")
     */
    public function addCompetence(CompetenceService $competenceService, Request $request){
        return $competenceService->addCompetence($request);
    }
}
