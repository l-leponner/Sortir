<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\ActivityRepository;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request,
                          ActivityRepository $activityRepository,
                          ParticipantRepository $participantRepository): Response
    {
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);



        if ($searchForm->isSubmitted() && $searchForm->isValid()){

            $lstActivities = $activityRepository->findAll();

            if ($searchForm->get('campus')->getData()){
                $lstActivities = $activityRepository->findBy(['campus' => $searchForm->get('campus')->getData()], ); //['dateTimeBeginning' => 'DESC']
            }

            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'searchForm' => $searchForm->createView(),
                'searchButton' => true,
                'lstActivities' => $lstActivities
            ]);
        }

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'searchForm' => $searchForm->createView(),
            'searchButton' => false
        ]);
    }
}
