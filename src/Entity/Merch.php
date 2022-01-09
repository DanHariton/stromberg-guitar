<?php

namespace App\Entity;

use App\Repository\MerchRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MerchRepository::class)
 */
class Merch
{
    const MERCH_VARS_LANG = ['name'];

    const MERCH_VARS = ['price', 'merchCategory'];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MerchCategory", inversedBy="merchs")
     * @ORM\JoinColumn(name="merch_category_id", referencedColumnName="id")
     */
    private $merchCategory;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageFilename;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $enabled;

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     * @return Merch
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFilename()
    {
        return $this->imageFilename;
    }

    /**
     * @param mixed $imageFilename
     * @return Merch
     */
    public function setImageFilename($imageFilename)
    {
        $this->imageFilename = $imageFilename;
        return $this;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getMerchCategory(): ?MerchCategory
    {
        return $this->merchCategory;
    }

    public function setMerchCategory(?MerchCategory $merchCategory): self
    {
        $this->merchCategory = $merchCategory;
        return $this;
    }
}
