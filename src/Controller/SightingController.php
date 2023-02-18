<?php

namespace App\Controller;

use App\Entity\Sighting;
use App\Entity\Taxon\Species;
use App\Form\SightingType;
use App\Repository\SightingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/sighting')]
class SightingController extends AbstractController {
	#[Route('/', name: 'app_sighting_index', methods: ['GET'])]
	public function index(SightingRepository $sightingRepository): Response{
		return $this->render('sighting/index.html.twig', [
			'sightings' => $sightingRepository->findAll(),
		]);
	}

	#[Route('/new/{species}', name: 'app_sighting_new', methods: ['GET', 'POST'])]
	#[IsGranted('ROLE_USER')]
	public function new(Request $request, EntityManagerInterface $entityManager, Species $species = null): Response{
		$sighting = new Sighting();
		if ($species)
			$sighting->setSpecies($species);

		$form = $this->createForm(SightingType::class, $sighting);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$sighting->setUser($this->getUser());
			$entityManager->persist($sighting);
			$entityManager->flush();

			return $this->redirectToRoute('app_sighting_index', [], Response::HTTP_SEE_OTHER);
		}

		return $this->render('sighting/new.html.twig', [
			'sighting' => $sighting,
			'form' => $form,
		]);
	}

	#[Route('/{id}', name: 'app_sighting_show', methods: ['GET'])]
	public function show(Sighting $sighting): Response{
		$species = $this->renderView('Partials/artfakta.html.twig', ['taxon' => $sighting->getSpecies()->getTaxonomyId()]);
		return $this->render('sighting/show.html.twig', [
			'sighting' => $sighting,
			'species' => $species
		]);
	}

	#[Route('/{id}/edit', name: 'app_sighting_edit', methods: ['GET', 'POST'])]
	public function edit(Request $request, Sighting $sighting, EntityManagerInterface $entityManager): Response{
		if ($sighting->getUser() !== $this->getUser()) throw  new UnauthorizedHttpException();

		$form = $this->createForm(SightingType::class, $sighting);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->flush();

			return $this->redirectToRoute('app_sighting_index', [], Response::HTTP_SEE_OTHER);
		}

		return $this->render('sighting/edit.html.twig', [
			'sighting' => $sighting,
			'form' => $form,
		]);
	}

	#[Route('/{id}', name: 'app_sighting_delete', methods: ['POST'])]
	public function delete(Request $request, Sighting $sighting, EntityManagerInterface $entityManager): Response{
		if ($sighting->getUser() !== $this->getUser()) throw  new UnauthorizedHttpException();

		if ($this->isCsrfTokenValid('delete' . $sighting->getId(), $request->request->get('_token'))) {
			$entityManager->remove($sighting);
			$entityManager->flush();
		}

		return $this->redirectToRoute('app_sighting_index', [], Response::HTTP_SEE_OTHER);
	}
}
