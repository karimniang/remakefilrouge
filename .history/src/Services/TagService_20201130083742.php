<?php

namespace App\Services;

use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;



Class TagService 
{
    private $repoTag;
    private $validator;
    private $manager;
    private $serializer;

    public function __construct(TagRepository $repoTag, ValidatorInterface $validator, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->repoTag = $repoTag;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    //Ajout d'un groupe de tag

    public function addGroupeTag($request){
        $data = $request->getContent();
        dd($data);
        $tags = $data->getTags();
        
        if (count($tags) < 1) {
            return new JsonResponse("Un tag est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $groupeTag = new GroupeTag();
        $groupeTag->setLibelle($data->getLibelle());
        $tabLibelle = [];


        foreach ($tags as $value) {
            if (!empty($value->getLibelle())) {
                $tag = $this->repo->findBy(array('libelle' => $value->getLibelle()));
                if ($tag) {
                    $groupeTag->addTag($tag[0]);
                } else {
                    if (!in_array($value->getlibelle(), $tabLibelle)) {
                        $tabLibelle[] = $value->getlibelle();
                        $tag = new Tag();
                        $tag->setLibelle($value->getLibelle());
                        $groupeTag->addTag($tag);
                    }
                }
            }
        }

        if (count($groupeTag->getTags())<1) {
            return new JsonResponse("Le libelle d'un tag est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $this->em->persist($groupeTag);
        $this->em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    
    }
}