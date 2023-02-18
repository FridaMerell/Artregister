<?php

namespace App\Entity\Taxon;

use App\Repository\Taxon\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull]
    private ?int $TaxonomyId;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotNull]
    private ?string $ScientificName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $VernacularName;

    #[ORM\ManyToOne(targetEntity: TaxClass::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotNull]
    private ?TaxClass $Class;

    #[ORM\OneToMany(mappedBy: 'TaxOrder', targetEntity: Family::class, orphanRemoval: true)]
    private Collection $Families;

    public function __construct()
    {
        $this->Families = new ArrayCollection();
    }

    
    function __toString() {
		return "{$this->VernacularName}  ({$this->ScientificName})";
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxonomyId(): ?int
    {
        return $this->TaxonomyId;
    }

    public function setTaxonomyId(int $TaxonomyId): self
    {
        $this->TaxonomyId = $TaxonomyId;

        return $this;
    }

    public function getScientificName(): ?string
    {
        return $this->ScientificName;
    }

    public function setScientificName(string $ScientificName): self
    {
        $this->ScientificName = $ScientificName;

        return $this;
    }

    public function getVernacularName(): ?string
    {
        return $this->VernacularName;
    }

    public function setVernacularName(?string $VernacularName): self
    {
        $this->VernacularName = $VernacularName;

        return $this;
    }

    public function getClass(): ?TaxClass
    {
        return $this->Class;
    }

    public function setClass(?TaxClass $Class): self
    {
        $this->Class = $Class;

        return $this;
    }

    /**
     * @return Collection<int, Family>
     */
    public function getFamilies(): Collection
    {
        return $this->Families;
    }

    public function addFamily(Family $family): self
    {
        if (!$this->Families->contains($family)) {
            $this->Families[] = $family;
            $family->setTaxOrder($this);
        }

        return $this;
    }

    public function removeFamily(Family $family): self
    {
        if ($this->Families->removeElement($family)) {
            // set the owning side to null (unless already changed)
            if ($family->getTaxOrder() === $this) {
                $family->setTaxOrder(null);
            }
        }

        return $this;
    }
}
