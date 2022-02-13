<?php

namespace App\Entity;

use App\Repository\GuitarVariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuitarVariantRepository::class)
 */
class GuitarVariant
{
    const VARS_LANG = [];
    const VARS = ['name'];

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
     * @ORM\ManyToOne(targetEntity="App\Entity\GuitarModel", inversedBy="variants")
     * @ORM\JoinColumn(name="guitar_model_id", referencedColumnName="id")
     */
    private $model;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GuitarColor", mappedBy="variant")
     */
    private $colors;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
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

    public function getModel(): ?GuitarModel
    {
        return $this->model;
    }

    public function setModel(?GuitarModel $model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addVariant(GuitarColor $color): self
    {
        if (!$this->colors->contains($color)) {
            $this->colors[] = $color;
            $color->setVariant($this);
        }

        return $this;
    }

    public function removeVariant(GuitarColor $color): self
    {
        if ($this->colors->removeElement($color)) {
            // set the owning side to null (unless already changed)
            if ($color->getVariant() === $this) {
                $color->setVariant(null);
            }
        }

        return $this;
    }

    /**
     * @param ArrayCollection $colors
     */
    public function setVariants(ArrayCollection $colors): void
    {
        $this->colors = $colors;
    }
}
