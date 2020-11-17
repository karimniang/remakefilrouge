<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Formateur;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UserProfilFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Query\AST\Functions\LowerFunction;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormateurFixtures extends Fixture implements DependentFixtureInterface 
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {

        $this->encoder = $encoder;
        
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');

            
            $profil = $this->getReference("formateur");
            for ($i = 0; $i < 2; $i++) {
                $formateur = new Formateur();
                $formateur->setusername( strtolower($profil->getLibelle()). $i);
                $formateur->setFirstname($faker->firstName());
                $formateur->setLastname($faker->lastName());
                
                $formateur->setProfil($profil);
                
                $password = $this->encoder->encodePassword($formateur, 'pass_1234');
                $formateur->setPassword($password);
                $manager->persist($formateur);
                
            }
        


        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserProfilFixtures::class,
        );
    }

    
}
