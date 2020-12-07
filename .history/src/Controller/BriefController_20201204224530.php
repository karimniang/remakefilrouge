<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\LivrableAttendu;
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
        $this->repoGroupe = $repoGroupe;
        $this->repoFormateur = $repoFormateur;
        $this->repoLivrableAttendu = $repoLivrableAttendu;
        $this->manager = $manager;
        $this->mailer = $mailer;
    }
    /**
     * @Route("/formateurs/briefs", name="add_brief", methods="POST")
     */
    public function addBrief(Request $request)
    {

        $data = $request->request->all();


        foreach ($data as $key => $value) {
            if ($key != "livrableAttendus") {
                if (is_array($value) && ($value[0] != "")) {
                    foreach ($data[$key] as $ke => $val) {
                        $data[$key][$ke] = 'api/admin/' . $key . '/' . $val;
                    }
                }
            }elseif ($key == "referentiel") {
                $data[$key] = 'api/admin/referentiels/' . $data[$key];
            }
        }

        $brief = $this->serializer->denormalize($data, Brief::class, true, ["groups" => "brief:write"]);
        
        // Traitement des livrables attendus: on les rattache au brief ou on les crée si nécessaire 
        if (isset($data['livrableAttendus'])) {
            $tabLibelle = [];
            foreach ($data['livrableAttendus'] as $value) {
                $livrableAttendu = $this->repoLivrableAttendu->findBy(array('libelle' => $value));
                if ($livrableAttendu) {
                    $brief->addLivrableAttendu($livrableAttendu[0]);
                } else {
                    if (!in_array($value, $tabLibelle)) {
                        $tabLibelle[] = $value;
                        $livrableAttendu = new LivrableAttendu();
                        $livrableAttendu->setLibelle($value);
                        $brief->addLivrableAttendu($livrableAttendu);
                    }
                }
            }
        }
        dd($brief);


        /*

            //Recupération référentiel 
        $referentielIri = 'api/admin/referentiels/' . $data["referentiel"];
        if (isset($data["referentiel"])) {
            $data["referentiel"] = $referentielIri;
        }


        //Récupération des compétences et des niveaux 
        if (isset($data['niveauCompetences'])) {
            foreach ($data['niveauCompetences'] as $key => $value) {
                $data['niveauCompetences'][$key] = 'api/niveau_evaluations/' . $value;
            }
        }
        
        //Récupération des tags 
        if (isset($data['tags'])) {
            foreach ($data['tags'] as $key => $value) {
                $data['tags'][$key] = 'api/admin/tags/' . $value;
            }
        }
        
        //Récupération du formateur connecté 
        $user = $this->getUser()->getId();
        $formateur = $repoFormateur->findBy(array('user' => $user));

        // Dénormalisation en briefs 
        $brief = $serializer->denormalize($data, Brief::class, true, ["groups" => "brief:write"]);
        $brief->setFormateur($formateur[0]);
        $brief->setDateCreation(new \DateTime());
        
        // Traitement de l'image et des pieces jointes 
        if (count($request->files) != 0){
            foreach ($request->files as $key => $value){
                if ($key == 'image'){
                    $brief->setImage($this->uploadFile($value, 'image'));
                } else{
                    $ressourceTab = $value;
                    foreach ($ressourceTab as $value){
                        $ressource = new Ressource();
                        $pieceJointe = $this->uploadFile($value, 'ressource');
                        $ressource->setPieceJointe($pieceJointe);
                        $brief->addRessource($ressource);
                    }
                }
            }
        }

        
        // Traitement des ressources de type URL 
        if (isset($data['ressource'])) {
            foreach ($data['ressource'] as $value) {
                $ressource = new Ressource();
                $ressource->setUrl($value);
                $brief->addRessource($ressource);
            }
        }

        // Traitement des livrables attendus: on les rattache au brief ou on les crée si nécessaire 
        if (isset($data['livrableAttendus'])) {
            $tabLibelle = [];
            foreach ($data['livrableAttendus'] as $value) {
                $livrableAttendu = $repoLivrableAttendu->findBy(array('libelle' => $value));
                if ($livrableAttendu) {
                    $brief->addLivrableAttendu($livrableAttendu[0]);
                } else {
                    if (!in_array($value, $tabLibelle)) {
                        $tabLibelle[] = $value;
                        $livrableAttendu = new LivrableAttendu();
                        $livrableAttendu->setLibelle($value);
                        $brief->addLivrableAttendu($livrableAttendu);
                    }
                }
            }
        }

        // Affecter EtatBrief 
        $errors = $validator->validate($brief);

        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }
        if (isset($data['groupes'])) {
            $etat = $repoEtatBrief->findBy(array('libelle' => 'ASSIGNE'));
        } elseif (isset($data['referentiel']) && isset($data['niveauCompetences']) && isset($data['tags'])) {
            $etat = $repoEtatBrief->findBy(array('libelle' => 'COMPLET'));
        } else {
            $etat = $repoEtatBrief->findBy(array('libelle' => 'BROUILLON'));
        }
        $brief->setEtatBrief($etat[0]);
        
        // Assignation du brief à un groupe 
        if (isset($data['groupes'])) {
            foreach ($data['groupes'] as $value) {
                $groupe = $repoGroupe->find($value);
                
                // Implémentation de EtatBriefGroupe 
                $etatBriefGroupe = new EtatBriefGroupe;
                $etatBriefGroupe->setBrief($brief);
                $etatBriefGroupe->setGroupe($groupe);
                $statut = $repoStatutBrief->findBy(array('libelle' => 'EN COURS'));
                $etatBriefGroupe->setStatut($statut[0]);
                
                // Implementation de BriefPromotion 
                $briefPromo = new BriefPromotion();
                $promo = $groupe->getPromotion();
                $briefPromo->setPromotion($promo);
                $briefPromo->setBrief($brief);
                $briefPromo->setStatut($statut[0]);
                $brief->addBriefPromotion($briefPromo);
                
                // Implementation du BriefApprenant 
                foreach ($groupe->getApprenants() as $value) {
                    $briefApprenant = new BriefApprenant();
                }
                
                // Envoi de mails aux apprenants assignés au brief
                foreach ($groupe->getApprenants() as $value) {
                    $this->sendEmail($mailer, $value, $brief);
                }
                
            }
        }
        
        $em->persist($brief);
        $em->flush();
        return new JsonResponse("succès.", Response::HTTP_CREATED, [], true);
        */
    }
}
