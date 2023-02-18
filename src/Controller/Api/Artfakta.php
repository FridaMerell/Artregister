<?php

namespace App\Controller\Api;

use App\Service\Artfakta as Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class Artfakta extends AbstractController {
	#[Route('/api/artfakta/{taxon}')]
	function artfakta(int $taxon, Api $artfakta): JsonResponse{
		$artfakta->setTaxa($taxon);
		$information = $artfakta->getTexts();
		return $this->json($information);
	}
}