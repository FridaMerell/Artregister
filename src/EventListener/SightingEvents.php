<?php

namespace App\EventListener;

use App\Entity\Card;
use App\Entity\Sighting;
use App\Entity\User;
use App\Repository\CardRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SightingEvents implements EventSubscriberInterface {
	function __construct(private readonly TokenStorageInterface $tokenStorage, private readonly CardRepository $cardRepository){
	}

	public function getSubscribedEvents(): array{
		return [
			Events::postPersist,
			Events::prePersist
		];
	}

	function prePersist(LifecycleEventArgs $args): void{
		$sighting = $args->getObject();
		if (!$sighting instanceof Sighting) return;
		/** @var User $user */
		$user = $this->tokenStorage->getToken()->getUser();
		$sighting->setUser($user);
	}

	function postPersist(LifecycleEventArgs $args): void{
		$sighting = $args->getObject();
		if (!$sighting instanceof Sighting) return;
		/** @var User $user */
		$user = $this->tokenStorage->getToken()->getUser();
		$cards = $user->getActiveCards($sighting->getDateTime());

		if (!empty($cards)) {
			/** @var Card $card */

			foreach ($cards as $card) {
				if ($card->hasSpecies($sighting->getSpecies())) {
					$card->addSighting($sighting);
					$this->cardRepository->save($card, true);
				}
			}
		}
	}
}