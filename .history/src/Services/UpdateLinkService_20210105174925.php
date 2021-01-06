<?php

namespace App\Services;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateLinkService 
{
    public function toAdded($elementsBrutes,$mere,$element,$repository){
        $toAdd = "add" . ucfirst(strtolower($element));
        $toRemove = "remove" . ucfirst(strtolower($element));
        $toGet = "get" . ucfirst(strtolower($element))."s";
        $entity = ucfirst(strtolower($element));
        $allCompetences =[];

    //Removed
        foreach ($elementsBrutes as $key) {
            $elements[] = $key["libelle"];
        }
        foreach ($mere->$toGet() as $toDelete) {

            if (!in_array($toDelete->getLibelle(), $elements)) {
                //dd("in");
                $mere->$toRemove($toDelete);
            }
        }

    //Added
        foreach ($mere->$toGet() as  $value) {
            $allCompetences[] = $value->getLibelle();
        }
        foreach ($elements as $newLibelle) {
            if (!in_array($newLibelle, $allCompetences)) {
                $elementAdded = $repository->findBy(array('libelle' => $newLibelle));
                if ($elementAdded) {
                    $mere->$toAdd($elementAdded[0]);
                }else {
                    $elementCreated = new $entity();
                    $elementCreated->setLibelle($newLibelle);
                    $mere->$toAdd($elementCreated);
                }
            }
        }
        return $mere;
    }

    //Pour les comp
    public function toAddedCompetence($elementsBrutes,$mere,$element,$repository){
        $toAdd = "add" . ucfirst(strtolower($element));
        $toRemove = "remove" . ucfirst(strtolower($element));
        $toGet = "get" . ucfirst(strtolower($element))."s";
        $entity = ucfirst(strtolower($element));
        $allCompetences =[];

    //Removed
        foreach ($elementsBrutes as $key) {
            $elements[] = $key;
        }
        foreach ($mere->$toGet() as $toDelete) {

            if (!in_array($toDelete->getLibelle(), $elements)) {
                //dd("in");
                $mere->$toRemove($toDelete);
            }
        }

    //Added
        foreach ($mere->$toGet() as  $value) {
            $allCompetences[] = $value->getLibelle();
        }
        foreach ($elements as $newLibelle) {
            if (!in_array($newLibelle, $allCompetences)) {
                $elementAdded = $repository->findBy(array('libelle' => $newLibelle));
                if ($elementAdded) {
                    $mere->$toAdd($elementAdded[0]);
                }else {
                    $elementCreated = new $entity();
                    $elementCreated->setLibelle($newLibelle);
                    $mere->$toAdd($elementCreated);
                }
            }
        }
        return $mere;
    }

    //pour les autres

    public function toSimpleUpdate($elementsBrutes,$mere,$element,$repository){
        $toAdd = "add" . ucfirst($element);
        $toRemove = "remove" . ucfirst($element);
        $toGet = "get" . ucfirst($element)."s";
        //$entity = ucfirst(strtolower($element));
        $allCompetences =[];

    //Removed
        foreach ($mere->$toGet() as $toDelete) {

            if (!in_array($toDelete->getLibelle(), $elementsBrutes)) {
                //dd("in");
                $mere->$toRemove($toDelete);
            }
        }

    //Added
        foreach ($mere->$toGet() as  $value) {
            $allCompetences[] = $value->getLibelle();
        }
        foreach ($elementsBrutes as $newLibelle) {
            if (!in_array($newLibelle, $allCompetences)) {
                $elementAdded = $repository->findBy(array('libelle' => $newLibelle));
                if ($elementAdded) {
                    $mere->$toAdd($elementAdded[0]);
                }
            }
        }
        return $mere;
    }
}