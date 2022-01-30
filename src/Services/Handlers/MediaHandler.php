<?php

namespace App\Services\Handlers;

use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class MediaHandler extends AbstractController
{
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function manageImages($request, $trick, $form, $imagePath)
    {
        /** @var UploadedFile $images */
        if (isset($request->files->all()['trick']['images'])) {
            $images = $request->files->all()['trick']['images'];
        }

        if (isset($images)) {
            $this->addImages($images, $trick, $imagePath);
        }
    }

    public function managePicture($request, $user, $imagePath)
    {
        /** @var UploadedFile $images */
        if (isset($request->files->all()['user']['picture'])) {
            $picture = $request->files->all()['user']['picture'];
        }

        if (isset($picture)) {
            $newPictureName = $this->addPicture($picture, $user, $imagePath);

            $user->setPicture($newPictureName);
        }
    }

    public function manageVideos($request, $trick)
    {
        $videosArray = [];

        if (isset($request->request->all()['trick']['videos'])) {
            $videos = $request->request->all()['trick']['videos'];

            foreach ($videos as $video) {
                if ($video['name'] != "") {
                    $videoName = $video['name'];
                    $video = new Video();
                    $video->setName($videoName);
                    $video->setTrick($trick);
                    array_push($videosArray, $video);
                };
            }
        }

        return $videosArray;
    }

    public function addImages($images, Trick $trick, $imagePath)
    {
        $arrayOfImageNames = [];

        foreach ($images as $key => $image) {
            if ($image['name'] != null) {
                $newImage = $this->uploadImage($image['name'], $imagePath);
                array_push($arrayOfImageNames, $newImage);
                $img = $trick->getImages()->toArray()[$key]->setName($newImage);
                $img->setTrick($trick);
                $trick->addImage($img);
            }
        }

        return $arrayOfImageNames;
    }

    public function addPicture($picture, User $user, $imagePath)
    {
        $newPicture = $this->uploadImage($picture, $imagePath);
        $user->setPicture($newPicture);

        return $newPicture;
    }



    public function uploadImage(UploadedFile $file, $imagePath)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $imagePath,
                $newFilename
            );
        } catch (FileException $e) {
            throw $e;
        }

        return $newFilename;
    }
}
