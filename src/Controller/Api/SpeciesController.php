<?php

namespace App\Controller\Api;

use App\Repository\Taxon\SpeciesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpeciesController extends AbstractController
{
    #[Route('/api/species?{s}', name: 'app_api_species')]
    public function getSpecies(string $s, SpeciesRepository $speciesRepository): Response
    {

    }
}
