<?php

namespace App\Controller;

use App\Repository\GroupeRepository;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LivrableAttenduRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\StatutBriefPromoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class BriefController extends AbstractController
{

    private $serializer;
    private $validator;
    private $statutBriefPromoRepository;
    private $repoGroupe;
    private $repoFormateur;
    private $repoLivrableAttendu;
    private $manager;
    private $mailer;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, StatutBriefPromoRepository $statutBriefPromoRepository, GroupeRepository $repoGroupe, FormateurRepository $repoFormateur, LivrableAttenduRepository $repoLivrableAttendu, EntityManagerInterface $manager, \Swift_Mailer $mailer)
  
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->statutBriefPromoRepository = $statutBriefPromoRepository;
        $this->repoGroupe =$repoGroupe;
        $this->repoFormateur = $repoFormateur;
        $this->repoLivrableAttendu = $repoLivrableAttendu;
        $this->manager = $manager;
        $this->mailer = $mailer;
    }
    /**
     * @Route("/formateurs/briefs", name="add_brief", methods="POST")
     */
    public function addBrief()
    {
        
    }
    
}
