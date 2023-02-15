<?php

namespace App\Entity\Taxon;

use App\Repository\Taxon\KingdomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: KingdomRepository::class)]
class Kingdom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotNull]
    private ?string $ScientificName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $VernacularName;

    #[ORM\OneToMany(mappedBy: 'Kingdom', targetEntity: Strain::class, orphanRemoval: true)]
    private ArrayCollection $strains;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull]
    private ?int $TaxonomyId;

    /**
     * species
     *
     * @var ?array
     */
    private $species;

    public function __construct()
    {
        $this->strains = new ArrayCollection();
    }


    function __toString()
    {
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

    /**
     * @return Collection<int, Strain>
     */
    public function getStrains(): Collection
    {
        return $this->strains;
    }

    public function addStrain(Strain $strain): self
    {
        if (!$this->strains->contains($strain)) {
            $this->strains[] = $strain;
            $strain->setKingdom($this);
        }

        return $this;
    }


    public function removeStrain(Strain $strain): self
    {
        if ($this->strains->removeElement($strain)) {
            // set the owning side to null (unless already changed)
            if ($strain->getKingdom() === $this) {
                $strain->setKingdom(null);
            }
        }

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


    public function getSpecies(): ?Collection
    {
        return $this->species;
    }
}
