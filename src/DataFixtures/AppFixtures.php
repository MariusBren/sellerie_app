<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $categories = [];

        // Création des catégories - début
        for ($p=0; $p < mt_rand(5, 10); $p++) {

            $category = new Category();
            $category->setCategoryName($faker->word());
            $manager->persist($category);

            $categories[] = $category;

        }
        // Création des catégories - fin

        // Création des produits - début
        for ($p=0; $p < mt_rand(50, 100); $p++) {

            $product = new Product();
            $product->setProductName($faker->word());
            $product->setDescription($faker->sentence());
            $product->setCategory($faker->randomElement($categories));
            $manager->persist($product);
        }
        // Création des produits - fin

        $manager->flush();
        
    }
}