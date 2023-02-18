<?php

namespace App\Controller\Api;

use App\Entity\Taxon\Species;
use App\Repository\Taxon\SpeciesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController {
	#[Route('/api/species/{s}', name: 'app_api_species')]
	public function getSpecies(string $s, SpeciesRepository $speciesRepository): Response{
		$query = $speciesRepository->createQueryBuilder('qb');
		$query->where('qb.VernacularName LIKE :s OR qb.ScientificName LIKE :s')
			->select('COUNT(s) AS HIDDEN sightings', 'qb')
			->leftJoin('qb.Sightings', 's')
			->orderBy('sightings', 'DESC')
			->groupBy('qb')
			->setMaxResults(10)
			->setParameter('s', "%{$s}%");
		$species = $query->getQuery()->getResult();

		if (count($species) == 0) return $this->json([]);
		$ret = array_map(function (Species $a){
			return [
				'id' => $a->getId(),
				'scientificName' => $a->getScientificName(),
				'vernacularName' => $a->getVernacularName()
			];
		}, $species);
		return $this->json($ret);
	}
}
