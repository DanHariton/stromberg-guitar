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
    const VARS_LANG = ['metaTitle', 'metaDescription', 'title', 'description', 'specs'];
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $specs;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

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

    public function getSpecs(): ?string
    {
        return $this->specs;
    }

    public function setSpecs(?string $specs): self
    {
        $this->specs = $specs;

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
