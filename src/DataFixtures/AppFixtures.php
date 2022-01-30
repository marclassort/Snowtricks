<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = [
            ['username' => 'fonctionnaire', 'first_name' => 'Thibaut', 'last_name' => 'Gruffy', 'email' => 'marc.lassort@gmail.com', 'password' => 'OpenClassrooms', 'role' => 'ROLE_USER'],
            ['username' => 'nabulione', 'first_name' => 'Marc', 'last_name' => 'Lassort', 'email' => 'marc.lassort@icloud.com', 'password' => 'OpenClassrooms', 'role' => 'ROLE_USER']
        ];

        $tricks = [
            ['name' => 'Underflip', 'description' => 'Frontside 180 mêlé à un backflip.', 'category' => 'Flips', 'content' => 'Le frontside underflip 540 est une figure qui mêle un frontside 180 et un backflip. Ce trick peut paraître intimidant, mais il n\'est pas si compliqué. Hormis le décollage, bien sûr. Ensuite, les mouvements peuvent s\'enchaîner assez naturellement.', 'slug' => 'Underflip', 'author' => 'fonctionnaire']
        ];

        $images = [
            ['name' => 'Underflip-61f3e2bdc6517.jpg']
        ];

        $videos = [
            ['name' => 'https://www.youtube.com/embed/XOcUznKGKTA']
        ];

        foreach ($users as $userArray) {
            $user = new User();

            $user->setUsername($userArray['username'])
                ->setFirstName($userArray['first_name'])
                ->setLastName($userArray['last_name'])
                ->setEmail($userArray['email'])
                ->setPassword($userArray['password'])
                ->setRole($userArray['role']);

            $manager->persist($user);
        }

        foreach ($images as $key => $imageArray) {
            $image = new Image();

            $trick = new Trick();

            $video = new Video();

            $image->setName($imageArray[$key]['name'])
                ->setTrick($trick);

            $video->setName($videos[$key]['name'])
                ->setTrick($trick);

            $trick->setName($tricks[$key]['name'])
                ->setDescription($tricks[$key]['description'])
                ->setCategory($tricks[$key]['category'])
                ->setContent($tricks[$key]['content'])
                ->setCreatedAt(new \DateTime)
                ->setSlug($tricks[$key]['slug'])
                ->setAuthor($tricks[$key]['author']);

            $manager->persist($image);
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
