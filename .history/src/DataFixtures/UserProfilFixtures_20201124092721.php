<?php

namespace App\DataFixtures;

use App\Entity\UserProfil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class UserProfilFixtures extends Fixture
{


    public const PROFIL_REFERENCE = 'profil';
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $profils = ["ADMIN", "FORMATEUR", "APPRENANT", "CM"];
        foreach ($profils as $libelle) {


            $profil = new UserProfil();
            $profil->setLibelle($libelle);
            $manager->persist($profil);

            if ($libelle == "ADMIN") {
                $this->addReference("admin", $profil);
            } elseif ($libelle == "APPRENANT") {
                $this->addReference("apprenant", $profil);
            } elseif ($libelle == "FORMATEUR") {
                $this->addReference("formateur", $profil);
            } elseif ($libelle == "CM") {
                $this->addReference("cm", $profil);
            }
        }

        $manager->flush();
    }
}
