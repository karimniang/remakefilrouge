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
        if (!isset($data['formateurs']) || empty($data['formateurs'])) {
            return new JsonResponse("Les formateurs sont obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        foreach ($data['formateurs'] as $value) {
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

        //Trait.. Apprenant
        $emailApprenat = array();
        if (!empty($request->files->get('fichier'))) {
            $filename = $request->files->get('fichier')->getRealPath();
            $emailApprenatEx = $this->readFileExcel($filename);
            foreach ($emailApprenatEx as $value) {
                $emailApprenat[] = $value[0];
            }
        }
        if (count($data['apprenants']) > 0) {
            foreach ($data['apprenants'] as $value) {
                if (!in_array($value, $emailApprenat)) {
                    $emailApprenat[] = $value;
                }
            }
        }
        if (count($emailApprenat) < 1) {
            return new JsonResponse("Les Apprenants sont obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }

        dd($emailApprenat);

    }

    // Fonction lire fichier excel
    public function readFileExcel($filename)
    {
        $reader = \PHPExcel_IOFactory::createReaderForFile($filename);
        $reader->setReadDataOnly(true);
        $wb = $reader->load($filename);
        $ws = $wb->getSheet(0);
        $rows = $ws->toArray();
        return $rows;
    }
}