<?php

namespace App\DataFixtures;

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
                ->setPhoneNumber($faker->phoneNumber())
                ->setEmail($faker->email())
                ->setPassword($faker->password())
                ->setRole("ROLE_USER");

            $manager->persist($user);

            for ($j = 1; $j <= mt_rand(3, 7); $j++) {
                $trick = new Trick();

                $content = '<p>' . implode('</p></p>', $faker->paragraphs(5)) . '</p>';

                $trick->setName($faker->words(3, true))
                    ->setDescription($faker->sentence())
                    ->setCategory($faker->word())
                    ->setContent($content)
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setAuthor($user)
                    ->setSlug($faker->slug(3));

                $manager->persist($trick);
            }
        }

        $manager->flush();
    }
}
