<?php

namespace App\Controller\Taxon;

use App\Entity\Taxon\Species;
use App\Form\Taxon\SpeciesType;
use App\Repository\Taxon\SpeciesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/taxon/species')]
class SpeciesController extends AbstractController
{
    #[Route('/', name: 'app_taxon_species_index', methods: ['GET'])]
    public function index(SpeciesRepository $speciesRepository): Response
    {
        return $this->render('taxon/species/index.html.twig', [
            'species' => $speciesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_taxon_species_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $species = new Species();
        $form = $this->createForm(SpeciesType::class, $species);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($species);
            $entityManager->flush();

            return $this->redirectToRoute('app_taxon_species_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('taxon/species/new.html.twig', [
            'species' => $species,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_taxon_species_show', methods: ['GET'])]
    public function show(Species $species): Response
    {
        return $this->render('taxon/species/show.html.twig', [
            'species' => $species,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_taxon_species_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Species $species, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SpeciesType::class, $species);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_taxon_species_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('taxon/species/edit.html.twig', [
            'species' => $species,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_taxon_species_delete', methods: ['POST'])]
    public function delete(Request $request, Species $species, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$species->getId(), $request->request->get('_token'))) {
            $entityManager->remove($species);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_taxon_species_index', [], Response::HTTP_SEE_OTHER);
    }
}
