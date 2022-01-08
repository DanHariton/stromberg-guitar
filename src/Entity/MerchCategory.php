<?php

namespace App\Entity;

use App\Repository\MerchCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MerchCategoryRepository::class)
 */
class MerchCategory
{
    const MERCH_CATEGORY_VARS_LANG = ['name'];

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
     * @ORM\OneToMany(targetEntity="App\Entity\Merch", mappedBy="merchCategory")
     */
    private $merchs;

    public function __construct()
    {
        $this->merchs = new ArrayCollection();
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

    public function getMerchs(): ArrayCollection
    {
        return $this->merchs;
    }

    public function setMerchs(ArrayCollection $merchs): void
    {
        $this->merchs = $merchs;
    }

    public function addMerch(Merch $merch) {
        if (!$this->merchs->contains($merch)) {
            $this->merchs[] = $merch;
            $merch->setMerchCategory($this);
        }

        return $this;
    }

    public function removeMerch(Merch $merch)
    {
        if ($this->merchs->removeElement($merch)) {
            // set the owning side to null (unless already changed)
            if ($merch->getMerchCategory() === $this) {
                $merch->setMerchCategory(null);
            }
        }

        return $this;
    }
}
