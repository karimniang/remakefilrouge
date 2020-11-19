<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UserProfilFixtures;
use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\Query\AST\Functions\LowerFunction;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture implements DependentFixtureInterface 
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

        
            
            $profil = $this->getReference("admin");
            for ($i = 0; $i < 2; $i++) {
                $admin = new Admin();
                $admin->setusername( strtolower($profil->getLibelle()). $i);
                $admin->setFirstname($faker->firstName());
                $admin->setLastname($faker->lastName());
                $admin->setEmail($faker->email);
                
                $admin->setProfil($profil);
                
                $password = $this->encoder->encodePassword($admin, 'pass_1234');
                $admin->setPassword($password);
                $manager->persist($admin);
                
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
