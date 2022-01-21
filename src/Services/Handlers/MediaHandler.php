<?php

namespace App\Services\Handlers;

use App\Entity\Trick;
use App\Entity\User;
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
            $arrayNames = $this->addImages($images, $trick, $imagePath);

            $newImages = $form->get('images')->getData();

            if ($arrayNames != []) {
                foreach ($newImages as $key => $newImage) {
                    if ($key != null) {
                        $newImage->setName($arrayNames[$key]);
                        $newImage->setTrick($trick);
                    }
                }
            }
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

    public function manageVideos($request, $trick, $form, $videoPath)
    {
        /** @var UploadedFile $videos */
        if (isset($request->files->all()['trick']['videos'])) {
            $videos = $request->files->all()['trick']['videos'];
        }

        if (isset($videos)) {
            $arrayNames = $this->addVideos($videos, $trick, $videoPath);

            $newVideos = $form->get('videos')->getData();

            if ($arrayNames != []) {
                foreach ($newVideos as $key => $newVideo) {
                    $newVideo->setName($arrayNames[$key - 1]);
                    $newVideo->setTrick($trick);
                }
            }
        }
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

    public function addVideos($videos, Trick $trick, $videoPath)
    {
        $arrayOfVideoNames = [];

        foreach ($videos as $key => $video) {
            if ($video['name'] != null) {
                $newVideo = $this->uploadVideo($video['name'], $videoPath);
                array_push($arrayOfVideoNames, $newVideo);
                $video = $trick->getVideos()->toArray()[$key]->setName($newVideo);
                $video->setTrick($trick);
                $trick->addVideo($video);
            }
        }

        return $arrayOfVideoNames;
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

    public function uploadVideo(UploadedFile $file, $videoPath)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $videoPath,
                $newFilename
            );
        } catch (FileException $e) {
            throw $e;
        }

        return $newFilename;
    }
}
