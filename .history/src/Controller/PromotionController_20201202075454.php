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
   
}
