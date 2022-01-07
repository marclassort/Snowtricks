<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Créer trois utilisateurs
        for ($i = 1; $i <= 3; $i++) {
            $user = new User();
            $user->setUsername($faker->name)
                ->setFirstName(explode(' ', trim($faker->name))[0])
                ->setLastName(explode(' ', trim($faker->name))[1])
                ->setEmail($faker->email())
                ->setPassword($faker->password())
                ->setRole("ROLE_USER");

            $manager->persist($user);

            for ($j = 1; $j <= mt_rand(3, 7); $j++) {
                $image = new Image();

                $trick = new Trick();

                $image->setName($faker->word())
                    ->setTrickUnique($trick);

                $content = '<p>' . implode('</p></p>', $faker->paragraphs(5)) . '</p>';

                $trick->setName($faker->words(3, true))
                    ->setDescription($faker->sentence())
                    ->setCategory($faker->word())
                    ->setContent($content)
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setSlug($faker->slug(3))
                    ->setAuthor($user)
                    ->setFirstImage($image);

                $manager->persist($image);
                $manager->persist($trick);
            }
        }

        $manager->flush();
    }
}
