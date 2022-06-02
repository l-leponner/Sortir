<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\ActivityRepository;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Repository\StateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Model\SearchActivityModel;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(Request $request,
                          ActivityRepository $activityRepository
                          ): Response
    {
        $currentParticipant = $this->getUser();


        $searchActivityModel = new SearchActivityModel();
//
//        $searchActivityModel->setParticipantCampus($currentParticipant->getCampus());

        $searchForm = $this->createForm(SearchType::class, $searchActivityModel);
        $searchForm->handleRequest($request);


        if ($searchForm->isSubmitted() && $searchForm->isValid()){

            $lstActivities = $activityRepository->findBy([], ['dateTimeBeginning' => 'DESC']);

            if ($searchForm->get('participantCampus')->getData()){
//                $lstActivitiesCampus = $activityRepository->findBy(['campus' => $searchForm->get('campus')->getData()], ); //['dateTimeBeginning' => 'DESC']
//                $lstActivities = $lstActivitiesCampus;
                $searchActivityModel->setParticipantCampus($searchForm->get('participantCampus')->getData());
            }

            if ($searchForm->get('nameKeyword')->getData()){
//                $keyWord = $searchForm->get('nameKeyword')->getData();
//                $lstActivitiesKeyword = $activityRepository->findByKeyWord($keyWord);
//                $lstActivities = $lstActivitiesKeyword;
                $searchActivityModel->setNameKeyword($searchForm->get('nameKeyword')->getData());
            }

            if ($searchForm->get('minDateTimeBeginning')->getData()){
                $searchActivityModel->setMinDateTimeBeginning($searchForm->get('minDateTimeBeginning')->getData());
            }

            if ($searchForm->get('maxDateTimeBeginning')->getData()){
                $searchActivityModel->setMaxDateTimeBeginning($searchForm->get('maxDateTimeBeginning')->getData());
            }

            if ($searchForm->get('filterActiOrganized')->getData()){
                $searchActivityModel->setFilterActiOrganized($searchForm->get('filterActiOrganized')->getData());
            }
            if ($searchForm->get('filterActiJoined')->getData()){
                $searchActivityModel->setFilterActiJoined($searchForm->get('filterActiJoined')->getData());
            }
            if ($searchForm->get('filterActiNotJoined')->getData()){
                $searchActivityModel->setFilterActiNotJoined($searchForm->get('filterActiNotJoined')->getData());
            }
            if ($searchForm->get('filterActiEnded')->getData()){
                $searchActivityModel->setFilterActiEnded($searchForm->get('filterActiEnded')->getData());
            }

            $lstActivities = $activityRepository->findByFilters($searchActivityModel, $currentParticipant);
            if (!$lstActivities){
                $this->addFlash("error", "Pas de sorties associées à la recherche.");
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
            'searchButton' => false,

        ]);
    }
}
