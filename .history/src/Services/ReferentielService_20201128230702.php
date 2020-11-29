<?php

namespace App\Services;

use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\ReferentielRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ReferentielService
{
    private $repoGroupeComp;
    private $validator;
    private $manager;
    private $serializer;
    private $userService;
    private $repoReferentiel;

    public function __construct(AddUser $userService, ReferentielRepository $repoReferentiel, GroupeCompetenceRepository $repoGroupeComp, ValidatorInterface $validator, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->repoGroupeComp = $repoGroupeComp;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->userService = $userService;
        $this->repoReferentiel = $repoReferentiel;
    }



    public function addReferentiel($request)
    {
        $data = $request->request->all();
        $referentiel = $this->serializer->denormalize($data, Referentiel::class, true, ["groups" => ["referentiel:write"]]);
        $errors = $this->validator->validate($referentiel);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }

        if (empty($data['groupeCompetences'])) {
            return new JsonResponse("Un groupe de compétences est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        foreach ($data['groupeCompetences'] as $value) {
            if ($value != "") {
                $groupeCompetence = $this->repoGroupeComp->findBy(array('libelle' => $value));
                if (!empty($groupeCompetence)) {
                    $referentiel->addGroupeCompetence($groupeCompetence[0]);
                } else {
                    return new JsonResponse("Le groupe de compétence ¨" . $value . "¨ n'existe pas !!!.", Response::HTTP_BAD_REQUEST, [], true);
                }
            }
        }
        if (count($referentiel->getGroupeCompetences()) < 1) {
            return new JsonResponse("Un groupe de compétence existant est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }


        $file = $request->files->get('programme');
        if (is_null($file)) {
            return new JsonResponse("Le programme est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $referentiel->setProgramme($this->userService->uploadFile($file, "programme"));
        //dd($referentiel);


        $this->manager->persist($referentiel);
        $this->manager->flush();

        return new JsonResponse("Referentiel added succesfully", Response::HTTP_CREATED, [], true);
    }

    public function showReferentiel($id_referentiel, $id_groupe)
    {

        $referentiel = $this->repoReferentiel->find($id_referentiel);
        if (is_null($referentiel)) {
            return new JsonResponse("Ce référentiel n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $groupeCompetence = $this->repoGroupeComp->find($id_groupe);
        if (is_null($groupeCompetence)) {
            return new JsonResponse("Ce groupe de compétences n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        foreach ($referentiel->getGroupeCompetences() as  $value) {
            if ($value == $groupeCompetence) {
                $referentielJson = $this->serializer->serialize($referentiel, 'json', ["groups" => ["referentiel:read_all"]]);
                return new JsonResponse($referentielJson, Response::HTTP_OK, [], true);
            } else {
                return new JsonResponse("Ce groupe de compétence n'est pas lié à ce référentiel.", Response::HTTP_BAD_REQUEST, [], true);
            }
        }
    }

    public function updateReferentiel($request, $id)
    {

        $data = $request->request->all();
        //dd($data);
        $referentiel = $this->repoReferentiel->find($id);
        if (is_null($referentiel)) {
            return new JsonResponse("Ce référentiel n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        //Archivage
        if (isset($data['deleted']) && $data['deleted']) {
            $referentiel->setDeleted(true);
            $this->manager->flush();
            return new JsonResponse('Référentiel archivé.', Response::HTTP_NO_CONTENT, [], true);
        }

        // Modification 
        foreach ($data as $key => $value) {
            if (isset($key) && !empty($key)) {
                if (($key != "_method") && ($key != "programme") && ($key != "groupeCompetences")) {
                    $toSet = "set" . ucfirst(strtolower($key));
                    $referentiel->$toSet($value);
                }
            }
        }
        //dd($referentiel);


        
        if (empty($data['groupeCompetences'])) {
            return new JsonResponse("Un groupe de compétences est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        foreach ($data['groupeCompetences'] as $value) {
            if ($value != "") {
                $libelle = explode("/", $value)[0];
                $action = explode("/", $value)[1];
                //dd($action);
                $groupeCompetenceDB = $this->repoGroupeComp->findBy(array('libelle' => $libelle));
                if (!empty($groupeCompetenceDB)) {
                    foreach ($referentiel->getGroupeCompetences() as $groupeCompetence) {
                        if ($groupeCompetence->getLibelle() == $libelle) {
                            if ($action == "supprimer") {
                                $referentiel->removeGroupeCompetence($groupeCompetenceDB[0]);
                            }elseif ($action == "ajouter") {
                                return new JsonResponse("Le groupe de compétence ´´" . $libelle . "´´ existe déjà dans ce referentiel.", Response::HTTP_BAD_REQUEST, [], true);
                            }
                        } else {
                            if ($action == "ajouter") {
                                $referentiel->addGroupeCompetence($groupeCompetenceDB[0]);
                            }else {
                                return new JsonResponse("Le groupe de compétence ´´" . $libelle . "´´ n'existe pas dans ce referentiel.", Response::HTTP_BAD_REQUEST, [], true);
                            }
                        }
                    }
                } else {
                    return new JsonResponse("Le groupe de compétence ´´" . $libelle . "´´ n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
                }
            }
        }
        if (count($referentiel->getGroupeCompetences()) < 1) {
            return new JsonResponse("Un groupe de compétence existant est requis pour enregistrer un referentiel.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $file = $request->files->get('programme');
        if (!is_null($file)) {
            $referentiel->setProgramme($this->userService->uploadFile($file, "programme"));
        }

        $this->manager->persist($referentiel);
        $this->manager->flush();
        $referentielUpdate = $this->serializer->serialize($referentiel, 'json');
        return new JsonResponse($referentielUpdate, Response::HTTP_OK, [], true);
    }
}
