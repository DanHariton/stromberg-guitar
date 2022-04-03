<?php

namespace App\Entity;

use App\Repository\GuitarSpecRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuitarSpecRepository::class)
 */
class GuitarSpec
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=GuitarModel::class, inversedBy="guitarSpecs")
     */
    private $guitar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGuitar(): ?GuitarModel
    {
        return $this->guitar;
    }

    public function setGuitar(?GuitarModel $guitar): self
    {
        $this->guitar = $guitar;

        return $this;
    }
}
