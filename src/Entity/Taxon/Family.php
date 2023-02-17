<?php

namespace App\Entity\Taxon;

use App\Repository\Taxon\FamilyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FamilyRepository::class)]
class Family
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

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'families')]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotNull]
    private ?Order $TaxOrder;

    #[ORM\OneToMany(mappedBy: 'Family', targetEntity: Genus::class, orphanRemoval: true)]
    private Collection $Genus;

    public function __construct()
    {
        $this->Genus = new ArrayCollection();
    }

    function __toString()
    {
        return (!empty($this->VernacularName)) ? $this->VernacularName : $this->ScientificName;
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
        return $this->VernacularName ?? $this->ScientificName;
    }

    public function setVernacularName(?string $VernacularName): self
    {
        $this->VernacularName = $VernacularName;

        return $this;
    }

    public function getTaxOrder(): ?Order
    {
        return $this->TaxOrder;
    }

    public function setTaxOrder(?Order $TaxOrder): self
    {
        $this->TaxOrder = $TaxOrder;

        return $this;
    }

    /**
     * @return Collection<int, Genus>
     */
    public function getGenus(): Collection
    {
        return $this->Genus;
    }

    public function addGenu(Genus $genu): self
    {
        if (!$this->Genus->contains($genu)) {
            $this->Genus[] = $genu;
            $genu->setFamily($this);
        }

        return $this;
    }

    public function removeGenu(Genus $genu): self
    {
        if ($this->Genus->removeElement($genu)) {
            // set the owning side to null (unless already changed)
            if ($genu->getFamily() === $this) {
                $genu->setFamily(null);
            }
        }

        return $this;
    }
}
