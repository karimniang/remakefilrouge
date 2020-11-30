<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupeTagController extends AbstractController
{
    /**
     * @Route("/groupe/tag", name="groupe_tag")
     */
    public function index(): Response
    {
        return $this->render('groupe_tag/index.html.twig', [
            'controller_name' => 'GroupeTagController',
        ]);
    }
}
