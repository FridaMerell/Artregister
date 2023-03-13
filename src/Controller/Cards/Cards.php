<?php

namespace App\Controller\Cards;

use App\Entity\Card;
use App\Form\NewCardType;
use App\Repository\CardRepository;
use App\Service\NewCard;
use Doctrine\DBAL\Driver\Middleware\AbstractConnectionMiddleware;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Cards extends AbstractController {
	function __construct(private readonly CardRepository $repository){
	}

	#[Route(path: '/cards/new', name: 'app_new_card')]
	#[IsGranted('ROLE_USER')]
	function new(Request $request, NewCard $cardHandler): Response{
		$form = $this->createForm(NewCardType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();
			$card = new Card;
			$card->setName($data['Name']);
			$cardHandler->setTemplate($data['Template']);
			$cardHandler->addSpecies($card);
			$cardHandler->addUsers($card, $data['Users']);
			$card->setStart($data['Start']);
			$card->setEnds($data['End']);

			$this->repository->save($card, true);
		}

		return $this->render('cards/new.html.twig', [
			'form' => $form->createView()
		]);
	}

	#[Route(path: '/cards', name: 'app_cards')]
	function list(): Response{
		$cards = $this->repository->findAll();
		return $this->render(view: 'cards/index.html.twig', parameters: [
			'cards' => $cards
		]);
	}

	#[Route('cards/{card}/delete')]
	#[IsGranted('ROLE_USER')]
	function delete(Card $card){
		$subscribers = $card->getSubscribers();
		if (!$subscribers->contains($this->getUser()))
			return $this->createAccessDeniedException();
		$this->repository->remove($card, true);
		return $this->redirectToRoute('app_cards');
	}

	#[Route(path: '/cards/{card}/edit')]
	#[IsGranted('ROLE_USER')]
	function edit(Card $card){
		return $this->render('cards/edit.html.twig', [
			'card' => $card
		]);
	}

	#[Route('/cards/{card}/show')]
	function show(Card $card){
		return $this->render('cards/show.html.twig', [
			'card' => $card
		]);
	}

	#[Route(path: '/cards/{card}/print')]
	function print(Card $card): Response{
		return $this->render('cards/print.html.twig', [
			'card' => $card
		]);
	}
}