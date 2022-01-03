<?php

namespace App\Services\Handlers;

use App\Entity\Image;
use App\Entity\Trick;
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
            $newImageName = $this->addImages($images, $trick, $imagePath);

            $newImages = $form->get('images')->getData();

            foreach ($newImages as $newImage) {
                $newImage->setName($newImageName);
                $newImage->setTrick($trick);
            }
        }
    }

    public function manageVideos($request, $trick, $form, $videoPath)
    {
        /** @var UploadedFile $videos */
        if (isset($request->files->all()['trick']['videos'])) {
            $videos = $request->files->all()['trick']['videos'];
        }

        if (isset($videos) && ($videos[0]['name'] != NULL)) {
            $newVideoName = $this->addVideos($videos, $trick, $videoPath);

            $newVideos = $form->get('videos')->getData();

            foreach ($newVideos as $newVideo) {
                $newVideo->setName($newVideoName);
                $newVideo->setTrick($trick);
            }
        }
    }

    public function addImages($images, Trick $trick, $imagePath)
    {
        foreach ($images as $key => $image) {
            $newImage = $this->uploadImage($image['name'], $imagePath);
            $img = $trick->getImages()->toArray()[$key]->setName($newImage);
            $img->setTrick($trick);
            $trick->addImage($img);
        }

        return $newImage;
    }

    public function addVideos($videos, Trick $trick, $videoPath)
    {
        foreach ($videos as $key => $video) {
            $newVideo = $this->uploadVideo($video['name'], $videoPath);
            $video = $trick->getVideos()->toArray()[$key]->setName($newVideo);
            $video->setTrick($trick);
            $trick->addVideo($video);
        }

        return $newVideo;
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

    public function deleteImages($request)
    {
        $images = $request->files->all()['trick']['images'];

        foreach ($images as $image) {
        }
    }
}
