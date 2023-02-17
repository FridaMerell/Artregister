<?php

namespace App\Entity\Taxon;

use App\Repository\Taxon\TaxClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaxClassRepository::class)]
class TaxClass {
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
	#[ORM\OneToMany(mappedBy: 'Class', targetEntity: Order::class, orphanRemoval: true)]
	private Collection $orders;

	public function __construct(){
		$this->orders = new ArrayCollection();
	}

	function __toString(){
		return (!empty($this->VernacularName)) ? $this->VernacularName : $this->ScientificName;
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


	/**
	 * @return Collection<int, Order>
	 */
	public function getOrders(): Collection{
		return $this->orders;
	}

	public function addOrder(Order $order): self{
		if (!$this->orders->contains($order)) {
			$this->orders[] = $order;
			$order->setClass($this);
		}

		return $this;
	}

	public function removeOrder(Order $order): self{
		if ($this->orders->removeElement($order)) {
			// set the owning side to null (unless already changed)
			if ($order->getClass() === $this) {
				$order->setClass(null);
			}
		}

		return $this;
	}
}
