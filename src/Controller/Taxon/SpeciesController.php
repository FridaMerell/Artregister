<?php

namespace App\Controller\Taxon;

use App\Entity\Taxon\Species;
use App\Repository\Taxon\SpeciesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rector\Parallel\ValueObject\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpeciesController extends AbstractController {
	function __construct(private readonly EntityManagerInterface $em, private readonly SpeciesRepository $repository){
	}

	#[Route(path: '/species/detail/{species}', name: 'app_species')]
	function view(Species $species): Response{
		$artfakta = $this->renderView('Partials/artfakta.html.twig', [
			'taxon' => $species->getTaxonomyId()
		]);
		return $this->render('taxon/species.html.twig', [
			'species' => $species,
			'artfakta' => $artfakta
		]);
	}

	#[Route(path: '/species/list/{p}')]
	function list(Request $request, int $p = 0): Response{
		$sort = 'qb.VernacularName';
		$maxResults = 50;
		$search = $request->query->get('search');
		$swedish = $request->query->get('swedish');

		$species = $this->repository->createQueryBuilder('qb')
			->setMaxResults($maxResults)
			->setFirstResult(max(0, $p * 50))
			->orderBy($sort, 'ASC');

		if ($swedish)
			$species->andWhere('qb.SwedishProminence = :swed')
				->setParameter('swed', 'Bofast och reproducerande');

		if ($search)
			$species->andWhere('qb.VernacularName LIKE :search')
				->setParameter('search', "%{$search}%");

		$species = $species->getQuery()
			->getResult();

		return $this->render('taxon/index.html.twig', [
			'species' => $species,
			'p'		=>	$p,
			'search'	=>	$search,
			'swedish'	=>	$swedish
		]);
	}
}