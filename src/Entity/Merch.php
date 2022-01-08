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

    const MERCH_VARS = ['price'];

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
     * @ORM\OneToOne(targetEntity="App\Entity\File", mappedBy="merch")
     */
    private $photo;

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

    public function getPhoto(): ?File
    {
        return $this->photo;
    }

    public function setPhoto(?File $photo): self
    {
        $this->photo = $photo;
        return $this;
    }
}
