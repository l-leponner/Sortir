<?php

namespace App\Controller\API;


use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PlaceController extends AbstractController
{
    #[Route('/api/place', name: 'api_place')]
    public function place(PlaceRepository $placeRepository, Request $request): Response
    {
        $data = json_decode($request->getContent());

        $place = $placeRepository->find($data->id);
        return $this->json(['street' => $place->getStreet(),
            'latitude' => $place->getLatitude(),
            'longitude' => $place->getLongitude(),
            'city'=>$place->getCity()->getName(), 'postCode' => $place->getCity()->getPostCode()]);
    }


}
