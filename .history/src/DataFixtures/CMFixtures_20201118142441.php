<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UserProfilFixtures;
use App\Entity\CM;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\Query\AST\Functions\LowerFunction;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CMFixtures extends Fixture implements DependentFixtureInterface 
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

     
            
            $profil = $this->getReference("cm");
            for ($i = 0; $i < 2; $i++) {
                $cm = new CM();
                $cm->setusername( strtolower($profil->getLibelle()). $i);
                $cm->setFirstname($faker->firstName());
                $cm->setLastname($faker->lastName());
                $cm->setEmail($faker->email);
                
                $cm->setProfil($profil);
                
                $password = $this->encoder->encodePassword($cm, 'pass_1234');
                $cm->setPassword($password);
                $manager->persist($cm);
                
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
