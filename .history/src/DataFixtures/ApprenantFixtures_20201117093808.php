<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UserProfilFixtures;
use App\Entity\Apprenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\Query\AST\Functions\LowerFunction;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture implements DependentFixtureInterface 
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

        
            
            $profil = $this->getReference("apprenant");
            for ($i = 0; $i < 2; $i++) {
                $apprenant = new Apprenant();
                $apprenant->setusername( strtolower($profil->getLibelle()). $i);
                $apprenant->setFirstname($faker->firstName());
                $apprenant->setLastname($faker->lastName());
                
                $apprenant->setProfil($profil);
                
                $password = $this->encoder->encodePassword($apprenant, 'pass_1234');
                $apprenant->setPassword($password);
                $manager->persist($apprenant);
                
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
