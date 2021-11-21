<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SnowtricksFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++)
        {
            $trick = new Trick();
            $trick->setName("Titre de la figure n°$i")
                    ->setDescription("<p>Description de la figure n°$i</p>")
                    ->setCategory("Groupe de la figure n°$i")
                    ->setContent("<p>Contenu de la figure n°$i</p>")
                    ->setCreationDate(new \DateTime)
                    ->setAuthor("Auteur");
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
