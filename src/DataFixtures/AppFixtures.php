<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Condition;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $categories = [];
        $condition_names = ['tout cassé','moyen cassé','un peu cassé','neuf'];

        // Création des catégories - début
        for ($p=0; $p < mt_rand(5, 10); $p++) {

            $category = new Category();
            $category->setCategoryName($faker->word());
            $manager->persist($category);

            $categories[] = $category;

        }
        // Création des catégories - fin

        // Création des états - début
        for ($p=0; $p < 4; $p++) {

            $condition = new Condition();
            $condition->setConditionName($condition_names[$p]);
            $manager->persist($condition);

            $conditions[] = $condition;

        }
        // Création des états - fin

        // Création des produits - début
        for ($p=0; $p < mt_rand(50, 100); $p++) {

            $product = new Product();
            $product->setProductName($faker->word());
            $product->setDescription($faker->sentence());
            $product->setCategory($faker->randomElement($categories));
            $product->setConditionState($faker->randomElement($conditions));
            $manager->persist($product);
        }
        // Création des produits - fin

        $manager->flush();
        
    }
}