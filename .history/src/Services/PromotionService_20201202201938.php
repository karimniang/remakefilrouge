<?php

namespace App\Services;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Groupe;
use App\Entity\Apprenant;
use App\Entity\Promotion;
use App\Repository\StatutRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromotionRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Repository\UserProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;




class PromotionService
{
    private $manager;
    private $validator;
    private $serializer;
    private $repoReferentiel;
    private $userService;
    private $repoFormateur;
    private $encoder;
    private $repoProfil;
    private $repoStatut;
    private $mailer;
    private $repoPromo;
    private $repoGroupe;
    private $repoPromotion;
    private $updateLinkService;

    public function __construct(
        \Swift_Mailer $mailer,
        ValidatorInterface $validator,
        UserPasswordEncoderInterface $encoder,
        FormateurRepository $repoFormateur,
        AddUser $userService,
        ReferentielRepository $repoReferentiel,
        EntityManagerInterface $manager,
        SerializerInterface $serializer,
        UserProfilRepository $repoProfil,
        StatutRepository $repoStatut,
        PromotionRepository $repoPromo,
        GroupeRepository $repoGroupe,
        PromotionRepository $repoPromotion,
        UpdateLinkService $updateLinkService
    ) {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->repoReferentiel = $repoReferentiel;
        $this->userService = $userService;
        $this->repoFormateur = $repoFormateur;
        $this->encoder = $encoder;
        $this->repoProfil = $repoProfil;
        $this->repoStatut = $repoStatut;
        $this->validator = $validator;
        $this->mailer = $mailer;
        $this->repoPromo = $repoPromo;
        $this->repoGroupe = $repoGroupe;
        $this->repoPromotion = $repoPromotion;
        $this->updateLinkService = $updateLinkService;
    }

    public function addPromo($request)
    {
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
                } else {
                    return new JsonResponse("Le referentiel ´´" . $value . "´´ n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
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
                } else {
                    return new JsonResponse("Le formateur ´´" . $value . "´´ n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
                }
            }
        }

        // Traitement Image --------------------
        $image = $request->files->get('image');
        if (is_null($image)) {
            return new JsonResponse("L'image est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $promotion->setImage($this->userService->uploadFile($image, "image"));

        //Trait.. Apprenant
        $emailApprenat = array();
        if (!empty($request->files->get('fichier'))) {
            $filename = $request->files->get('fichier')->getRealPath();
            $emailApprenatEx = $this->readFile($filename);
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

        //dd($emailApprenat);
        foreach ($emailApprenat as $value) {
            if (!empty($value)) {
                $apprenant = new Apprenant();
                $apprenant->setEmail($value);
                $password = "1234-" . $value[4] . $value[0] . $value[3];
                $apprenant->setPassword($this->encoder->encodePassword(new Apprenant(), $password));
                $apprenant->setUsername(explode("@", $value)[0]);
                $apprenant->setFirstname("firstname");
                $apprenant->setLastname("lastname");
                $apprenant->setProfil($this->repoProfil->findBy(['libelle' => "APPRENANT"])[0]);
                $apprenant->setStatut($this->repoStatut->find(1));
                if ($promotion->addApprenant($apprenant)) {
                    $groupe->addApprenant($apprenant);
                    $apprenant->sendEmail($this->mailer, $password);
                    //dd($apprenant->getUsername());
                }
            }
        }

        // validation du promotion ----------------------------
        $errors = $this->validator->validate($promotion);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }



        // L'insertion du promotion -----------------------------
        //$this->manager->persist($promotion);
        //$this->manager->flush();
        //dd($promotion);
        //$promoAdded = $this->serializer->serialize($promotion, 'json');
        return new JsonResponse("successs", Response::HTTP_CREATED, [], true);
    }

    public function showApp()
    {
        $promos = $this->repoPromo->findAll();
        foreach ($promos as $value) {
            foreach ($value->getApprenants() as  $apprenant) {
                if (!$apprenant->getAttente()) {
                    $value->removeApprenant($apprenant);
                }
            }
        }
        $promosJson = $this->serializer->serialize($promos, 'json');
        return new JsonResponse($promosJson, Response::HTTP_OK, [], true);
    }

    public function showAppById($id)
    {
        $promo = $this->repoPromo->find($id);
        if (!$promo) {
            return new JsonResponse("Cette promo n'existe pas", Response::HTTP_NOT_FOUND, [], true);
        }

        foreach ($promo->getApprenants() as  $apprenant) {
            if (!$apprenant->getAttente()) {
                $promo->removeApprenant($apprenant);
            }
        }

        $promosJson = $this->serializer->serialize($promo, 'json');
        return new JsonResponse($promosJson, Response::HTTP_OK, [], true);
    }

    public function showAppByGroupe($id_promo, $id_groupe)
    {

        $groupe = $this->repoGroupe->findBy(["id" => $id_groupe, "promotion" => $id_promo]);
        //dd($groupe);
        if (empty($groupe)) {
            return new JsonResponse("Cette promo n'est pas lié à ce groupe", Response::HTTP_NOT_FOUND, [], true);
        }

        $promosJson = $this->serializer->serialize($groupe, 'json', ["groups" => ["promo_groupe_apprenants:read"]]);
        return new JsonResponse($promosJson, Response::HTTP_OK, [], true);
    }

    public function editReferentielForPromo($id, $request)
    {
        $editPromotion = $this->repoPromotion->find($id);
        $data = $request->request->all();
        $promotion = $this->serializer->denormalize($data, Promotion::class, true, ["groups" => "promotion:write"]);

        //Archivage
        /*if (isset($data['deleted']) && $data['deleted']) {
            $editPromotion->setDeleted(true);
            $this->manager->flush();
            return new JsonResponse('Référentiel archivé.', Response::HTTP_NO_CONTENT, [], true);
        }*/

        // Modification 
        foreach ($data as $key => $value) {
            if (isset($key) && !empty($key)) {
                if (($key != "_method") && ($key != "programme") && ($key != "groupes") && ($key != "referentiels") && ($key != "apprenants") && ($key != "formateurs")) {
                    $toSet = "set" . ucfirst(strtolower($key));
                    $toGet = "get" . ucfirst(strtolower($key));
                    $editPromotion->$toSet($promotion->$toGet());
                }
            }
        }
        // Traitement referentiels ---------------------------
        
        foreach ($data['referentiels'] as $value) {
            $referentielsBrutes[]= $value;
        }
        //dd($referentielsBrutes);
        $editPromotion = $this->updateLinkService->toSimpleUpdate($referentielsBrutes,$editPromotion,"referentiel",$this->repoReferentiel);

        if (count($editPromotion->getReferentiels()) < 1) {
            return new JsonResponse("Les referentiels sont obligatoires", Response::HTTP_BAD_REQUEST, [], true);
        }

        // Traitement Image -------------------------------
        $image = $request->files->get('image');
        if (!is_null($image)) {
            $newImage = $this->userService->uploadFile($image,"image_promo");
            $editPromotion->setimage($newImage);
        }
        dd($editPromotion);

        // validation du promotion ----------------------------
        $errors = $validator->validate($promotion);
        if (($errors) > 0) {
            $errorsString = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }

        // L'insertion du promotion ------------------------
        if ($editPromotion) {
            $em->persist($editPromotion);
            $em->persist($editPromotion);
            $em->flush();
        }
        return new JsonResponse('success', Response::HTTP_CREATED, [], true);
       
        /*

        // Verification des dates et modification des date --------------------- 
        if ($editPromotion->getDateDebut() > $promotion->getDateFin()) {
            return new JsonResponse("La date de fin doit etre superieur a la date de debut.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $editPromotion->setDateFin($promotion->getDateFin());
        $editPromotion->setTitre($promotion->getTitre());
        $editPromotion->setDescription($promotion->getDescription());
        $editPromotion->setLieu($promotion->getLieu());
        $editPromotion->setReferenceAgate($promotion->getReferenceAgate());

        // Traitement Langue -------------------------------
        if (empty($promotionTab['langue'])) {
            return new JsonResponse("La langue est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $editPromotion->setLangue($repoLangue->findBy(array('libelle' => $promotionTab['langue']))[0]);

        // Traitement Fabrique ------------------------------
        if (!isset($promotionTab['fabrique']) || empty($promotionTab['fabrique'])) {
            return new JsonResponse("La langue est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $editPromotion->setFabrique($repoFabrique->findBy(array('libelle' => $promotionTab['fabrique']))[0]);

        

         */
    }



    // Fonction lire fichier excel
    public function readFile($doc)
    {
        if ($doc) {
            $file = IOFactory::identify($doc);
            $reader = IOFactory::createReader($file);
            $spreadsheet = $reader->load($doc);
            $fichierexcel = $spreadsheet->getActivesheet()->toArray();
            return $fichierexcel;
        }
    }
}
