<?php

namespace App\Entity\Taxon;

use App\Repository\Taxon\StrainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StrainRepository::class)]
class Strain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $ScientificName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $VernacularName;

    #[ORM\Column(type: 'integer')]
    private ?int $TaxonomyId;

    #[ORM\ManyToOne(targetEntity: Kingdom::class, inversedBy: 'strains')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Kingdom $Kingdom;

    #[ORM\OneToMany(mappedBy: 'Strain', targetEntity: TaxClass::class, orphanRemoval: true)]
    private ArrayCollection $taxClasses;

    public function __construct()
    {
        $this->taxClasses = new ArrayCollection();
    }

    
    function __toString() {
        return (!empty($this->VernacularName)) ? $this->VernacularName : $this->ScientificName;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTaxonomyId(): ?int
    {
        return $this->TaxonomyId;
    }

    public function setTaxonomyId(int $TaxonomyId): self
    {
        $this->TaxonomyId = $TaxonomyId;

        return $this;
    }

    public function getKingdom(): ?Kingdom
    {
        return $this->Kingdom;
    }

    public function setKingdom(?Kingdom $Kingdom): self
    {
        $this->Kingdom = $Kingdom;

        return $this;
    }

    /**
     * @return Collection<int, TaxClass>
     */
    public function getTaxClasses(): Collection
    {
        return $this->taxClasses;
    }

    public function addTaxClass(TaxClass $taxClass): self
    {
        if (!$this->taxClasses->contains($taxClass)) {
            $this->taxClasses[] = $taxClass;
            $taxClass->setStrain($this);
        }

        return $this;
    }

    public function removeTaxClass(TaxClass $taxClass): self
    {
        if ($this->taxClasses->removeElement($taxClass)) {
            // set the owning side to null (unless already changed)
            if ($taxClass->getStrain() === $this) {
                $taxClass->setStrain(null);
            }
        }

        return $this;
    }
}
