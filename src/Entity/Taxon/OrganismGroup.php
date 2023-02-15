<?php

namespace App\Entity\Taxon;

use App\Repository\Taxon\OrganismGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrganismGroupRepository::class)]
class OrganismGroup
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

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull]
    private ?int $TaxonomyId;

    #[ORM\OneToMany(targetEntity: Species::class, mappedBy: 'organismGroup')]
    private ArrayCollection $Species;

    public function __construct()
    {
        $this->Species = new ArrayCollection();
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

    /**
     * @return Collection<int, Species>
     */
    public function getSpecies(): Collection
    {
        return $this->Species;
    }

    public function addSpecies(Species $species): self
    {
        if (!$this->Species->contains($species)) {
            $this->Species[] = $species;
            $species->setOrganismGroup($this);
        }

        return $this;
    }

    public function removeSpecies(Species $species): self
    {
        if ($this->Species->removeElement($species)) {
            // set the owning side to null (unless already changed)
            if ($species->getOrganismGroup() === $this) {
                $species->setOrganismGroup(null);
            }
        }

        return $this;
    }
}
