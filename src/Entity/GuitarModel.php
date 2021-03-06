<?php

namespace App\Entity;

use App\Repository\GuitarModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuitarModelRepository::class)
 */
class GuitarModel
{
    const VARS_LANG = ['metaTitle', 'metaDescription', 'title', 'description'];
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GuitarVariant", mappedBy="model")
     */
    private $variants;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity=GuitarSpec::class, mappedBy="guitar")
     */
    private $guitarSpecs;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
        $this->guitarSpecs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getNameSlug(): ?string
    {
        return strtolower(str_replace(' ', '-', (string)$this->name));
    }

    public function getPreview()
    {
        /** @var GuitarVariant $variant */
        foreach ($this->getVariants() as $variant) {
            /** @var GuitarColor $color */
            foreach ($variant->getColors() as $color) {
                /** @var File $image */
                foreach ($color->getImages() as $image) {
                    return $image->getFileName();
                }
            }
        }

        return null;
    }

    public function getGallery()
    {
        $images = [];

        /** @var GuitarVariant $variant */
        foreach ($this->getVariants() as $variant) {
            /** @var GuitarColor $color */
            foreach ($variant->getColors() as $color) {
                /** @var File $image */
                foreach ($color->getImages() as $image) {
                    $images[] = $image->getFileName();
                    break;
                }
            }
        }

        return $images;
    }

    public function getAnyColor(): ?GuitarColor
    {
        foreach ($this->getVariants() as $variant) {
            foreach ($variant->getColors() as $color) {
                return $color;
            }
        }

        return null;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function getDefaultVariant(): ?GuitarVariant
    {
        /** @var GuitarVariant[] $variants */
        $variants = $this->getVariants()->getValues();

        foreach ($variants as $variant) {
            if ($variant->getIsDefault()) {
                return $variant;
            }
        }

        return null;
    }

    public function addVariant(GuitarVariant $variant): self
    {
        if (!$this->variants->contains($variant)) {
            $this->variants[] = $variant;
            $variant->setModel($this);
        }

        return $this;
    }

    public function removeVariant(GuitarVariant $variant): self
    {
        if ($this->variants->removeElement($variant)) {
            // set the owning side to null (unless already changed)
            if ($variant->getModel() === $this) {
                $variant->setModel(null);
            }
        }

        return $this;
    }

    /**
     * @param ArrayCollection $variants
     */
    public function setVariants(ArrayCollection $variants): void
    {
        $this->variants = $variants;
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

    /**
     * @return Collection<int, GuitarSpec>
     */
    public function getGuitarSpecs(): Collection
    {
        return $this->guitarSpecs;
    }

    public function addGuitarSpec(GuitarSpec $guitarSpec): self
    {
        if (!$this->guitarSpecs->contains($guitarSpec)) {
            $this->guitarSpecs[] = $guitarSpec;
            $guitarSpec->setGuitar($this);
        }

        return $this;
    }

    public function removeGuitarSpec(GuitarSpec $guitarSpec): self
    {
        if ($this->guitarSpecs->removeElement($guitarSpec)) {
            // set the owning side to null (unless already changed)
            if ($guitarSpec->getGuitar() === $this) {
                $guitarSpec->setGuitar(null);
            }
        }

        return $this;
    }
}
