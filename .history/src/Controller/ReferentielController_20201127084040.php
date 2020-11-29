<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 */

class ReferentielController extends AbstractController
{
    /**
     * @Route(name="add_referentiel", path="/admin/referentiels", methods={"POST"})
     */

    public function addReferentiel(){

    }

    /**
     * @Route(name="edit_referentiel", path="/admin/referentiels/{id}", methods={"PUT"})
     */

    public function editReferentiel(){

    }

    /**
     * @Route(name="show_groupe_referentiel_id", path="/api/admin/referentiels/{id_referentiel}/groupe_competences/{id_groupe}", methods={"GET"})
     */
    public function showRefGroupeById(){
        
    }
     
}
