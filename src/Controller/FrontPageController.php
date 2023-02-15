<?php

namespace App\Controller;

use App\Form\FindSpeciesType;
use App\Repository\SightingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontPageController extends AbstractController
{
    #[Route('/', name: 'app_front_page')]
    public function index(SightingRepository $sightingRepository): Response
    {
        $findSpecies = $this->createForm(FindSpeciesType::class)->createView();
        $sightings = $sightingRepository->findAll();
        return $this->render('front_page/index.html.twig', [
            'controller_name' => 'FrontPageController',
            'sightings' => $sightings,
            'find_species' => $findSpecies
        ]);
    }
}
