<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceCreationType;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlaceCreationController extends AbstractController
{
    #[Route('/place/creation', name: 'place_creation')]
    public function createPlace(Request $request, PlaceRepository $placeRepository): Response
    {

        $place = new Place();

        $placeCreationForm = $this->createForm(PlaceCreationType::class, $place);
        $placeCreationForm->handleRequest($request);

        if ($placeCreationForm->isSubmitted() && $placeCreationForm->isValid()){
            $placeRepository->add($place, true);
            $this->addFlash("success","Lieu enregistré.");
            return $this->redirectToRoute('index');
        }
        return $this->render('place_creation/creation.html.twig', [
            'controller_name' => 'PlaceCreationController',
            'placeCreationForm' => $placeCreationForm->createView()
        ]);
    }
}
