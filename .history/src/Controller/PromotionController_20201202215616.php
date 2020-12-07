<?php

namespace App\Controller;

use App\Services\PromotionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class PromotionController extends AbstractController
{

    /**
     * @Route("/admin/promotions", name="add_promotion", methods="POST")
     */

    public function addPromotion(Request $request, PromotionService $promotionService){
        return $promotionService->addPromo($request);
    }

    /**
     * @Route("/admin/promotions/apprenants/attente", name="show_apprenants_attente", methods="GET")
     */

    public function showAppByStatut(PromotionService $promotionService){
        return $promotionService->showApp();
    }
   
    /**
     * @Route("/admin/promotions/{id}/apprenants/attente", name="show_apprenants_id_attente", methods="GET")
     */

    public function showAppByStatutByIdPromo(PromotionService $promotionService, $id){
        return $promotionService->showAppById($id);
    }

    /**
     * @Route("/admin/promotions/{id_promo}/groupes/{id_groupe}/apprenants", name="show_apprenants_id_attente", methods="GET")
     */

    public function showAppByGroupeByPromo(PromotionService $promotionService, $id_promo, $id_groupe){
        return $promotionService->showAppByGroupe($id_promo, $id_groupe);
    }

    /**
     * @Route("/admin/promotions/{id}/referentiels", name="edit_promotion_referentiles", methods="PUT")
     */

    public function EditPromoAndReferentiel(PromotionService $promotionService, $id, Request $request){
        return $promotionService->editReferentielForPromo($id, $request);
    }

    /**
     * @Route("/admin/promotion/{id}/formateurs", name="edit_promotion_formateurs", methods="PUT")
     */
    public function editPromotionFormateurs(PromotionService $promotionService, Request $request, $id){
        return $promotionService->editFormateurForPromo($id, $request);
    }
}
