<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = [
            ['username' => 'fonctionnaire', 'first_name' => 'Thibaut', 'last_name' => 'Gruffy', 'email' => 'marc.lassort@gmail.com', 'password' => 'OpenClassrooms', 'role' => 'a:0:{}']
        ];

        $tricks = [
            ['name' => 'Underflip', 'description' => 'Frontside 180 mêlé à un backflip.', 'category' => 'Flips', 'content' => 'Le frontside underflip 540 est une figure qui mêle un frontside 180 et un backflip. Ce trick peut paraître intimidant, mais il n\'est pas si compliqué. Hormis le décollage, bien sûr. Ensuite, les mouvements peuvent s\'enchaîner assez naturellement.', 'slug' => 'Underflip', 'author' => 'fonctionnaire'],
            ['name' => 'Slopestyle', 'description' => 'Tricks acrobatiques sur une descente avec plusieurs obstacles', 'category' => 'Grabs', 'content' => 'C\'est l\'une des disciplines les plus populaires les plus impressionnantes des Winter X Games. Le but ? Réaliser des tricks acrobatiques sur une descente qui compte plusieurs obstacles comme des tremplins, des bosses ou des rampes.', 'slug' => 'slopestyle', 'author' => 'fonctionnaire'],
            ['name' => 'McTwist', 'description' => 'Flip agrémenté d\'une vrille', 'category' => 'Rotation', 'content' => 'Le Mc Twist est un flip (rotation verticale) agrémenté d\'une vrille. Un saut plutôt périlleux réservé aux riders les plus confirmés. Le champion Shaun White s\'est illustré par un Double Mc Twist 1260 lors de sa session de Half-Pipe aux Jeux Olympiques de Vancouver en 2010.', 'slug' => 'mctwist', 'author' => 'fonctionnaire'],
            ['name' => 'Lipslide', 'description' => 'Glisser sur un obstacle avec la planche perpendiculaire', 'category' => 'Slides', 'content' => 'Le lispslide consiste à glisser sur un obstacle en mettant la planche perpendiculaire à celui-ci. Un jib à 90 degrés en d\'autres termes. Le lipslide peut se faire en avant ou en arrière. Frontside ou backside, donc.', 'slug' => 'lipslide', 'author' => 'fonctionnaire'],
            ['name' => 'Half Pipe', 'description' => 'Descente d\'un tremplin en forme de U et remontée avec figures', 'category' => 'Grabs', 'content' => 'C\'est un double tremplin en forme de U. Les snowboarders doivent réussir à descendre le long de la rampe d’un côté et remonter le plus vite possible de l’autre côté, en réussissant des tricks quand ils sont en l\'air. Un half-pipe classique mesure environ 5 mètres de haut et 120 mètres de long.', 'slug' => 'half-pipe', 'author' => 'fonctionnaire'],
            ['name' => 'Big Air', 'description' => 'Saut d\'un tremplin avec maximum de figures', 'category' => 'Grabs', 'content' => 'C\'est l\'une des épreuves les plus impressionnantes dans les compétitions de snow. Le rider s’élance à une vitesse folle avant de sauter sur une tremplin et de réaliser un maximum de tricks dans les airs. Le big air peut aussi faire référence au tremplin de neige duquel le snowboardeur s\'élance avant de faire ses figures', 'slug' => 'big-air', 'author' => 'fonctionnaire'],
            ['name' => 'Air to Fakie', 'description' => 'Saut sans rotation dans un pipe', 'category' => 'Grabs', 'content' => 'Il s\'agit d\'une figure relativement simple, et plus précisément d\'un saut sans rotation qui se fait généralement dans un pipe (un U). Le rider s\'élance dans les airs et retombe dans le sens inverse.', 'slug' => 'air-to-fakie', 'author' => 'fonctionnaire'],
            ['name' => 'Stalefish', 'description' => 'Saisie de la carre backside de la planche', 'category' => 'Grabs', 'content' => 'Consiste à saisir la carre backside de la planche entre les deux pieds avec la main arrière.', 'slug' => 'stalefish', 'author' => 'fonctionnaire'],
            ['name' => 'Switch ollie', 'description' => 'Un ollie est la figure de base du skateboard. Il s\'agit d\'un saut effectué avec la planche.', 'category' => 'Grabs', 'content' => 'Le mouvement à effectuer est le suivant : il s\'agit tout d\'abord de claquer le « tail » (la queue, l\'arrière de la planche). L\'avant de la planche monte. Après cela, il faut gratter le grip avec son pied avant tout en montant les genoux pour faire décoller l\'arrière de la planche.', 'slug' => 'switch-ollie', 'author' => 'fonctionnaire'],
        ];

        $images = [
            ['name' => 'Underflip-61f3e2bdc6517.jpg'],
            ['name' => 'Slopestyle-61f570ba872bf.jpg'],
            ['name' => 'McTwist-61eaac34c4b34.jpg'],
            ['name' => 'Lipslide-61eaac5e5e232.jpg'],
            ['name' => 'half-pipe-61eaac681700e.jpg'],
            ['name' => 'big-air-61eaac6f5f063.jpg'],
            ['name' => 'air-to-fakie-61eaac762799d.jpg'],
            ['name' => 'stalefish-61eaac7e1cbfe.jpg'],
            ['name' => 'switch-ollie-61eaac8541dbb.jpg']
        ];

        $videos = [
            ['name' => 'https://www.youtube.com/embed/XOcUznKGKTA'],
            ['name' => 'https://www.youtube.com/embed/0qjGBcDFy3A'],
            ['name' => 'https://www.youtube.com/embed/YQIvm_2ay-U'],
            ['name' => 'https://www.youtube.com/embed/LSVn5aI56aU'],
            ['name' => 'https://www.youtube.com/embed/yKovI9hMjBs'],
            ['name' => 'https://www.youtube.com/embed/leMHlvHwygo'],
            ['name' => 'https://www.youtube.com/embed/9sVe_IiXD3A'],
            ['name' => 'https://www.youtube.com/embed/f9FjhCt_w2U'],
            ['name' => 'https://www.youtube.com/embed/LPxoK1ej7r0'],
        ];

        foreach ($users as $userArray) {
            $user = new User();

            $passwordHasher = new UserPasswordHasherInterface();

            $user->setUsername($userArray['username'])
                ->setFirstName($userArray['first_name'])
                ->setLastName($userArray['last_name'])
                ->setEmail($userArray['email'])
                ->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $userArray['password']
                    )
                )
                ->setRole($userArray['role'])
                ->setIsVerified(1);

            $manager->persist($user);
        }

        foreach ($images as $key => $imageArray) {
            $image = new Image();

            $trick = new Trick();

            $video = new Video();

            $image->setName($imageArray['name'])
                ->setTrick($trick);

            $video->setName($videos[$key]['name'])
                ->setTrick($trick);

            $trick->setName($tricks[$key]['name'])
                ->setDescription($tricks[$key]['description'])
                ->setCategory($tricks[$key]['category'])
                ->setContent($tricks[$key]['content'])
                ->setCreatedAt(new \DateTime)
                ->setSlug($tricks[$key]['slug'])
                ->setAuthor($user);

            $manager->persist($image);
            $manager->persist($video);
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
