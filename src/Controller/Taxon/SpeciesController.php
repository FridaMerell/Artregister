<?php

namespace App\Controller\Taxon;

use App\Entity\Taxon\Species;
use Rector\Parallel\ValueObject\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpeciesController extends AbstractController {
	#[Route(path: '/species/{species}', name: 'app_species')]
	function view(Species $species): Response{
		$artfakta = $this->renderView('Partials/artfakta.html.twig', [
			'taxon' => $species->getTaxonomyId()
		]);
		return $this->render('taxon/species.html.twig', [
			'species' => $species,
			'artfakta' => $artfakta
		]);
	}
}