<?php

namespace App\Entity\Taxon;

use App\Entity\Card;
use App\Entity\Sighting;
use App\Repository\Taxon\SpeciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpeciesRepository::class)]
class Species {
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
	#[ORM\ManyToOne(targetEntity: Genus::class, inversedBy: 'species')]
	#[ORM\JoinColumn(nullable: true)]
	#[Assert\NotNull]
	private ?Genus $Genus;
	private ?Family $Family;
	#[ORM\OneToMany(mappedBy: 'Species', targetEntity: Sighting::class, orphanRemoval: true)]
	private Collection $Sightings;
	#[ORM\ManyToMany(targetEntity: Card::class, mappedBy: 'Species')]
	#[ORM\JoinTable(name: 'card_species')]
	private Collection $Cards;
	#[ORM\Column(length: 255, nullable: true)]
	private ?string $SwedishProminence = null;

	public function __construct(){
		$this->Sightings = new ArrayCollection();
		$this->Cards = new ArrayCollection();
	}

	function __toString(){
		return "{$this->VernacularName}  ({$this->ScientificName})";
	}

	public function getId(): ?int{
		return $this->id;
	}

	public function getTaxonomyId(): ?int{
		return $this->TaxonomyId;
	}

	public function setTaxonomyId(int $TaxonomyId): self{
		$this->TaxonomyId = $TaxonomyId;

		return $this;
	}

	public function getScientificName(): ?string{
		return $this->ScientificName;
	}

	public function setScientificName(string $ScientificName): self{
		$this->ScientificName = $ScientificName;

		return $this;
	}

	public function getVernacularName(): ?string{
		return $this->VernacularName;
	}

	public function setVernacularName(?string $VernacularName): self{
		$this->VernacularName = $VernacularName;

		return $this;
	}

	public function getGenus(): ?Genus{
		return $this->Genus;
	}

	public function setGenus(?Genus $Genus): self{
		$this->Genus = $Genus;

		return $this;
	}

	/**
	 * @return Collection<int, Sighting>
	 */
	public function getSightings(): Collection{
		return $this->Sightings;
	}

	public function addSighting(Sighting $sighting): self{
		if (!$this->Sightings->contains($sighting)) {
			$this->Sightings[] = $sighting;
			$sighting->setSpecies($this);
		}

		return $this;
	}

	public function removeSighting(Sighting $sighting): self{
		if ($this->Sightings->removeElement($sighting)) {
			// set the owning side to null (unless already changed)
			if ($sighting->getSpecies() === $this) {
				$sighting->setSpecies(null);
			}
		}

		return $this;
	}

	function getFullName(): string{
		return "{$this->VernacularName} ({$this->ScientificName})";
	}

	function getFamily(): ?Family{
		return $this->Genus->getFamily();
	}

	/**
	 * @return Collection<int, Card>
	 */
	public function getCards(): Collection{
		return $this->Cards;
	}

	public function addCard(Card $card): self{
		if (!$this->Cards->contains($card)) {
			$this->Cards->add($card);
			$card->addSpecies($this);
		}

		return $this;
	}

	public function removeCard(Card $card): self{
		if ($this->Cards->removeElement($card)) {
			$card->removeSpecies($this);
		}

		return $this;
	}

	public function getSwedishProminence(): ?string{
		return $this->SwedishProminence;
	}

	public function setSwedishProminence(string $SwedishProminence): self{
		$this->SwedishProminence = $SwedishProminence;

		return $this;
	}
}
