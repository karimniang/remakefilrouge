<?php

namespace App\Controller;

use App\Entity\GroupeCompetence;
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
     * @Route("/admin/grpecompetences", name="add_groupe_competence", methods ="POST", 
     * defaults={
     *         "_api_resource_class"=GroupeCompetence::class,
     *         "_api_collection_operation_name"="post_groupe_competence"
     *     })
     */
    public function addGroupeCompetence(GroupeCompetenceService $groupeCompetenceService, Request $request){
        return $groupeCompetenceService->addGroupeCompetence($request);
    }
}
