<?php

namespace App\Entity;

use App\Entity\Taxon\Species;
use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card {
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;
	#[ORM\Column(length: 255)]
	private ?string $Name = null;
	#[ORM\ManyToMany(targetEntity: Species::class, inversedBy: 'Cards')]
	#[ORM\JoinTable(name: 'card_species')]
	private Collection $Species;
	#[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'Cards')]
	private Collection $Subscribers;
	#[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
	private ?\DateTimeInterface $Start = null;
	#[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
	private ?\DateTimeInterface $Ends = null;
	#[ORM\ManyToMany(targetEntity: Sighting::class, inversedBy: 'Cards')]
	#[ORM\JoinTable(name: 'card_sightings')]
	private Collection $Sightings;

	public function __construct(){
		$this->Species = new ArrayCollection();
		$this->Subscribers = new ArrayCollection();
		$this->Sightings = new ArrayCollection();
	}

	public function getId(): ?int{
		return $this->id;
	}

	public function getName(): ?string{
		return $this->Name;
	}

	public function setName(string $Name): self{
		$this->Name = $Name;

		return $this;
	}

	/**
	 * @return Collection<int, Species>
	 */
	public function getSpecies(): Collection{
		return $this->Species;
	}

	public function addSpecies(Species $species): self{
		if (!$this->Species->contains($species)) {
			$this->Species->add($species);
		}

		return $this;
	}

	public function removeSpecies(Species $species): self{
		$this->Species->removeElement($species);

		return $this;
	}

	/**
	 * @return Collection<int, User>
	 */
	public function getSubscribers(): Collection{
		return $this->Subscribers;
	}

	public function addSubscriber(User $subscriber): self{
		if (!$this->Subscribers->contains($subscriber)) {
			$this->Subscribers->add($subscriber);
		}

		return $this;
	}

	public function removeSubscriber(User $subscriber): self{
		$this->Subscribers->removeElement($subscriber);

		return $this;
	}

	public function getStart(): ?\DateTimeInterface{
		return $this->Start;
	}

	public function setStart(?\DateTimeInterface $Start): self{
		$this->Start = $Start;

		return $this;
	}

	public function getEnds(): ?\DateTimeInterface{
		return $this->Ends;
	}

	public function setEnds(?\DateTimeInterface $Ends): self{
		$this->Ends = $Ends;

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
			$this->Sightings->add($sighting);
		}

		return $this;
	}

	public function removeSighting(Sighting $sighting): self{
		$this->Sightings->removeElement($sighting);

		return $this;
	}
}
