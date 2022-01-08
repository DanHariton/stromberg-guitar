<?php

namespace App\Service;

use Exception;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageResizer
{
    private UploadedFile $file;

    /** @var resource */
    private $image;

    /** @var int */
    private $width;

    /** @var int */
    private $height;

    /** @var int */
    private $type;

    /**
     * @param int $width
     * @param int $height
     */
    public function resizeTo($width, $height)
    {
        $source_aspect_ratio = $this->width / $this->height;
        $desired_aspect_ratio = $width / $height;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            $temp_height = $height;
            $temp_width = ( int ) ($height * $source_aspect_ratio);
        } else {
            $temp_width = $width;
            $temp_height = ( int ) ($width / $source_aspect_ratio);
        }

        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled(
            $temp_gdim,
            $this->image,
            0, 0,
            0, 0,
            $temp_width, $temp_height,
            $this->width, $this->height
        );

        $x0 = ($temp_width - $width) / 2;
        $y0 = ($temp_height - $height) / 2;
        $desired_gdim = imagecreatetruecolor($width, $height);
        imagecopy(
            $desired_gdim,
            $temp_gdim,
            0, 0,
            $x0, $y0,
            $width, $height
        );

        $this->image = $desired_gdim;
    }

    /**
     * @param string $pathForSave
     * @return bool
     */
    public function save($pathForSave)
    {
        if (!is_resource($this->image)) {
            return false;
        }

        if ($this->type == IMAGETYPE_PNG) {
            $result = imagepng($this->image, $pathForSave, 5);
        } else {
            $result = imagejpeg($this->image, $pathForSave, 85);
        }

        return $result;
    }

    /**
     * @return resource
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param SplFileInfo|UploadedFile $file
     * @throws Exception
     */
    public function setImage($file)
    {
        $this->file = $file;

        if ($this->file && $this->file->getRealPath() && file_exists($this->file->getRealPath())) {
            list($this->width, $this->height, $this->type) = getimagesize($this->file->getRealPath());

            $ext = mb_strtolower($this->file->getClientOriginalExtension());

            switch ($ext) {
                case in_array($ext, ['jpg', 'jpeg']):
                    $this->image = imagecreatefromjpeg($this->file->getRealPath());
                    break;
                case in_array($ext, ['bmp']):
                    $this->image = imagecreatefromwbmp($this->file->getRealPath());
                    break;
                case in_array($ext, ['png']):
                    $this->image = imagecreatefrompng($this->file->getRealPath());
                    break;
                default:
                    throw new Exception("ImageResize class not provide resizing for file with this type: {$ext}");
                    break;
            }
        }
    }
}