<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Exception;

class FileUploader
{
    private $targetDirectoryImg;

    private SluggerInterface $slugger;

    /**
     * FileUploader constructor.
     * @param $targetDirectoryImg
     * @param SluggerInterface $slugger
     */
    public function __construct($targetDirectoryImg, SluggerInterface $slugger)
    {
        $this->targetDirectoryImg = $targetDirectoryImg;
        $this->slugger = $slugger;
    }

    /**
     * @param UploadedFile $file
     * @return string
     * @throws Exception
     */
    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            throw new Exception('Soubor se nepodařilo uložit, ' . $e->getMessage());
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectoryImg;
    }
}