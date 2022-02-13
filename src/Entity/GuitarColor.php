<?php

namespace App\Entity;

use App\Repository\GuitarColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\JoinColumn(name="guitar_variant_id", referencedColumnName="id")
     */
    private $variant;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="guitarColor")
     */
    private $images;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

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

    /**
     * @return Collection|File[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(File $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setGuitarColor($this);
        }

        return $this;
    }

    public function removeImage(File $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getGuitarColor() === $this) {
                $image->setGuitarColor(null);
            }
        }

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
