<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FileRepository::class)
 */
class File
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist", inversedBy="files")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id")
     */
    private $artist;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Merch", inversedBy="photo")
     * @ORM\JoinColumn(name="merch_id", referencedColumnName="id")
     */
    private $merch;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getMerch(): ?Merch
    {
        return $this->merch;
    }

    public function setMerch(?Merch $merch): self
    {
        $this->merch = $merch;

        return $this;
    }
}
