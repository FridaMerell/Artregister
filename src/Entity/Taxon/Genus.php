<?php

namespace App\Entity\Taxon;

use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\Taxon\GenusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenusRepository::class)]
class Genus {
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

    #[ORM\ManyToOne(targetEntity: Family::class, inversedBy: 'Genus')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Family $Family;

    #[ORM\OneToMany(mappedBy: 'Genus', targetEntity: Species::class, orphanRemoval: true)]
    private ArrayCollection $species;

    public function __construct() {
        $this->species = new ArrayCollection();
    }

    function __toString() {
        return (!empty($this->VernacularName)) ? $this->VernacularName : $this->ScientificName;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTaxonomyId(): ?int {
        return $this->TaxonomyId;
    }

    public function setTaxonomyId(int $TaxonomyId): self {
        $this->TaxonomyId = $TaxonomyId;

        return $this;
    }

    public function getScientificName(): ?string {
        return $this->ScientificName;
    }

    public function setScientificName(string $ScientificName): self {
        $this->ScientificName = $ScientificName;

        return $this;
    }

    public function getVernacularName(): ?string {
        return $this->VernacularName;
    }

    public function setVernacularName(?string $VernacularName): self {
        $this->VernacularName = $VernacularName;

        return $this;
    }

    public function getFamily(): ?Family {
        return $this->Family;
    }

    public function setFamily(?Family $Family): self {
        $this->Family = $Family;

        return $this;
    }

    /**
     * @return Collection<int, Species>
     */
    public function getSpecies(): Collection {
        return $this->species;
    }

    public function addSpecies(Species $species): self {
        if (!$this->species->contains($species)) {
            $this->species[] = $species;
            $species->setGenus($this);
        }

        return $this;
    }

    public function removeSpecies(Species $species): self {
        if ($this->species->removeElement($species)) {
            // set the owning side to null (unless already changed)
            if ($species->getGenus() === $this) {
                $species->setGenus(null);
            }
        }

        return $this;
    }
}
