<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 */
class GroupeTagController extends AbstractController
{
    /**
     * @Route(
     *      name="add_groupe_tag", path="/admin/groupe_tags", methods={"POST"})
     */

    public function addGroupeTag(Request $request){

    }

    

    /**
     * @Route(
     *      name="edit_groupe_tag", path="/admin/groupe_tags/{id}", methods={"PUT"})
     */

    public function editGroupeTag(Request $request){
        
    }
}
