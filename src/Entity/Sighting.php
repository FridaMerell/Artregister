<?php

namespace App\Entity;

use App\Entity\Taxon\Species;
use App\Repository\SightingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: SightingRepository::class)]
#[ORM\Table(name:'taxon_species')]
class Sighting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'Sightings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User;

    #[ORM\ManyToOne(targetEntity: Species::class, inversedBy: 'Sightings')]
    #[ORM\JoinColumn(nullable: false)]
	#[ORM\JoinTable(name:'sighting_species')]
    private ?Species $Species;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $Location;

    #[ORM\Column(type: 'datetime')]
    private ?DateTime $DateTime;

    #[ORM\Column(type: 'string', length: 512, nullable: true)]
    private ?string $Comment;

    #[ORM\ManyToMany(targetEntity: Card::class, mappedBy: 'Sightings')]
    private Collection $Cards;

    public function __construct()
    {
        $this->Cards = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->Species;
    }

    public function setSpecies(?Species $Species): self
    {
        $this->Species = $Species;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->Location;
    }

    public function setLocation(?string $Location): self
    {
        $this->Location = $Location;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->DateTime;
    }

    public function setDateTime(\DateTimeInterface $DateTime): self
    {
        $this->DateTime = $DateTime;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->Comment;
    }

    public function setComment(?string $Comment): self
    {
        $this->Comment = $Comment;

        return $this;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getCards(): Collection
    {
        return $this->Cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->Cards->contains($card)) {
            $this->Cards->add($card);
            $card->addSighting($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->Cards->removeElement($card)) {
            $card->removeSighting($this);
        }

        return $this;
    }
}
