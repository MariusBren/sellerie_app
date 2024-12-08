<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Repair;
use DateTimeImmutable;
use App\Entity\History;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Location;
use App\Entity\Condition;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher) {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $categories = [];
        $conditions = [];
        $locations = [];
        $products = [];
        $condition_names = ['tout cassé','moyen cassé','un peu cassé','neuf'];
        $sections_names = ['section alpha','section beta','section delta'];
        $shelves_names = ['étagère n°1','étagère n°2','étagère n°3'];

        // Création des catégories - début
        for ($p=0; $p < mt_rand(5, 10); $p++) {

            $category = new Category();
            $category->setCategoryName($faker->colorName());
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

        // Création des emplacements - début
        for ($p=0; $p < mt_rand(5, 10); $p++) {

            $location = new Location();
            $location->setSection($faker->word());
            $location->setShelf($faker->word());
            $manager->persist($location);

            $locations[] = $location;

        }
        // Création des emplacements - fin

        // Création des produits - début
        for ($p=0; $p < mt_rand(50, 100); $p++) {

            $product = new Product();
            $product->setProductName($faker->word());
            $product->setDescription($faker->sentence());
            $product->setCategory($faker->randomElement($categories));
            $product->setConditionState($faker->randomElement($conditions));
            $product->setLocation($faker->randomElement($locations));
            $manager->persist($product);

            $products[] = $product;
        }
        // Création des produits - fin

        // Création des historiques - début
        for ($p=0; $p < mt_rand(5, 10); $p++) {

            $history = new History();
            $history->setProduct($faker->randomElement($products));
            $history->setDescription($faker->sentence());
            $history->setStartDate(new \DateTimeImmutable());
            $history->setEndDate(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('now', '6 months')));
            $history->setCustomer($faker->lastname());
            $history->setBack($faker->boolean());
            $manager->persist($history);
        }
        // Création des historiques - fin

        // Création des réparations - début
        for ($p=0; $p < mt_rand(5, 10); $p++) {

            $history = new Repair();
            $history->setProduct($faker->randomElement($products));
            $history->setDescription($faker->sentence());
            $history->setDate(new \DateTime());
            $history->setDone($faker->boolean());
            $history->setCost(mt_rand(5, 5000));
            $manager->persist($history);
        }
        // Création des réparations - fin

        // Création des utilisateurs - début
        for ($u=0; $u < 5; $u++) {

            $user = new User;
            $password = $this->hasher->hashPassword($user, 'password');

            // Créer 1 admin et 4 utilisateurs
            if ($u==0) {
                $user->setRoles(["ROLE_ADMIN"])
                ->setEmail("admin@test.com");
            } else {
                $user->setEmail($faker->email())
                ->setRoles([]);
            }

            $user->setPassword($password);

            $manager->persist($user);   
        }
        // Création des utilisateurs - fin

        $manager->flush();
        
    }
}