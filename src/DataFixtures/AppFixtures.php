<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        // Création des produits - début
        for ($p=0; $p < mt_rand(50, 100); $p++) {

            $product = new Product();
            $product->setProductName($faker->word());
            $product->setDescription($faker->sentence());
            $manager->persist($product);
        }
        // Création des produits - fin

        $manager->flush();
        
    }
}