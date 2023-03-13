<?php /** @noinspection ALL */

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface {
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;
	#[ORM\Column(length: 180, unique: true)]
	private ?string $email = null;
	#[ORM\Column]
	private array $roles = [];
	/**
	 * @var ?string The hashed password
	 */
	#[ORM\Column]
	private ?string $password = null;
	#[ORM\OneToMany(mappedBy: 'User', targetEntity: Sighting::class, orphanRemoval: true)]
	private Collection $Sightings;
	#[ORM\Column(type: 'boolean')]
	private $isVerified = false;
	#[ORM\ManyToMany(targetEntity: Card::class, mappedBy: 'Subscribers')]
	private Collection $Cards;

	public function __construct(){
		$this->Sightings = new ArrayCollection();
		$this->Cards = new ArrayCollection();
	}

	function __toString(): string{
		return $this->getUserIdentifier();
	}

	public function getId(): ?int{
		return $this->id;
	}

	public function getEmail(): ?string{
		return $this->email;
	}

	public function setEmail(string $email): self{
		$this->email = $email;

		return $this;
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUserIdentifier(): string{
		return (string)$this->email;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	public function setRoles(array $roles): self{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * @see PasswordAuthenticatedUserInterface
	 */
	public function getPassword(): string{
		return $this->password;
	}

	public function setPassword(string $password): self{
		$this->password = $password;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials(){
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
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
			$sighting->setUser($this);
		}

		return $this;
	}

	public function removeSighting(Sighting $sighting): self{
		if ($this->Sightings->removeElement($sighting)) {
			// set the owning side to null (unless already changed)
			if ($sighting->getUser() === $this) {
				$sighting->setUser(null);
			}
		}

		return $this;
	}

	public function isVerified(): bool{
		return $this->isVerified;
	}

	public function setIsVerified(bool $isVerified): self{
		$this->isVerified = $isVerified;

		return $this;
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
			$card->addSubscriber($this);
		}

		return $this;
	}

	public function removeCard(Card $card): self{
		if ($this->Cards->removeElement($card)) {
			$card->removeSubscriber($this);
		}

		return $this;
	}

	function getActiveCards(): array{
		$cards = [];
		/** @var Card $card */
		foreach ($this->Cards as $card) {
			if (!$card->getEnds() && !$card->getStart())
				$cards[] = $card;
			if ($card->getStart() < new \DateTime() || $card->getEnds() > new \DateTime()) continue;
		}

		return $cards;
	}
}
