<?php

namespace App\Entity;

use App\Repository\GuitarColorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuitarColorRepository::class)
 */
class GuitarColor
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
     * @ORM\ManyToOne(targetEntity="App\Entity\GuitarVariant", inversedBy="colors")
     * @ORM\JoinColumn(name="guitar_color_id", referencedColumnName="id")
     */
    private $variant;

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

    public function getVariant(): ?GuitarVariant
    {
        return $this->variant;
    }

    public function setVariant(?GuitarVariant $variant)
    {
        $this->variant = $variant;
        return $this;
    }
}
