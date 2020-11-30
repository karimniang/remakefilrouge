<?php

namespace App\Services;

use App\Entity\Groupe;
use App\Entity\Promotion;
use App\Repository\FormateurRepository;
use App\Repository\ReferentielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;


class PromotionService
{
    private $manager;
    private $serializer;
    private $repoReferentiel;
    private $userService;
    private $repoFormateur;

    public function __construct(FormateurRepository $repoFormateur, AddUser $userService,ReferentielRepository $repoReferentiel,EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->repoReferentiel = $repoReferentiel;
        $this->userService = $userService;
        $this->repoFormateur = $repoFormateur;
    }

    public function addPromo($request){
        $data = $request->request->all();
        $promotion = $this->serializer->denormalize($data, Promotion::class, true, ["groups" => "promotion:write"]);

        if ($promotion->getDateDebut() > $promotion->getDateFin()) {
            return new JsonResponse("La date de fin doit etre superieur a la date de debut.", Response::HTTP_BAD_REQUEST, [], true);
        }

        // Traitement Groupes 
        $groupe = new Groupe();
        $groupe->setLibelle("Groupe principal");
        $groupe->setDateCreation($promotion->getDateDebut());
        $promotion->addGroupe($groupe);
        
        // Trait.. referentiel
        foreach ($data['referentiels'] as $value) {
            if (!empty($value)) {
                $referentiel = $this->repoReferentiel->findBy(array('libelle' => $value));
                if ($referentiel) {
                    $promotion->addReferentiel($referentiel[0]);
                }else {
                    return new JsonResponse("Le referentiel ´´".$value."´´ n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
                }   
            }
        }

        // Trait... Formateurs
        if (!isset($promotionTab['formateurs']) || empty($promotionTab['formateurs'])) {
            return new JsonResponse("Les formateurs sont obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        foreach ($promotionTab['formateurs'] as $value) {
            if (!empty($value)) {
                $formateur = $this->repoFormateur->find($value);
                if ($formateur) {
                    $promotion->addFormateur($formateur);
                }else {
                    return new JsonResponse("Le formateur ´´".$value."´´ n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
                }
            }
        }
       
        // Traitement Image --------------------
        $image = $request->files->get('image');
        if (is_null($image)) {
            return new JsonResponse("L'image est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $promotion->setImage($this->userService->uploadFile($image,"image"));

        dd($promotion);

    }
}