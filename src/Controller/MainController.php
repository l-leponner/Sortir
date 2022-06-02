<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Participant;
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
                          ActivityRepository $activityRepository,
                          StateRepository $stateRepository
                          ): Response
    {
        $currentParticipant = $this->getUser();
//        $lstActivities = $activityRepository->findBy([], ['dateTimeBeginning' => 'DESC']);

        $searchActivityModel = new SearchActivityModel();
//
//        $searchActivityModel->setParticipantCampus($currentParticipant->getCampus());

        $searchForm = $this->createForm(SearchType::class, $searchActivityModel);
        $searchForm->handleRequest($request);


        if ($searchForm->isSubmitted() && $searchForm->isValid()){

            $lstActivities = $activityRepository->findBy([], ['dateTimeBeginning' => 'DESC']);


            $state = $stateRepository->findOneBy(['wording' => 'Activity opened']);
            $lstActivities = $activityRepository->findByFilters($searchActivityModel, $currentParticipant, $state);
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
//            'lstActivities' => $lstActivities
        ]);
    }

    #[Route('/desist/{activity}', name: 'desist')]
    public function desist(Activity $activity, ActivityRepository $activityRepository): Response
    {
        /**
         * @var Participant $currentParticipant
         */
        $currentParticipant = $this->getUser();
        $activity->removeParticipant($currentParticipant);
        $activityRepository->add($activity, true);

        $this->addFlash("success","Désistement validé.");
        return $this->redirectToRoute('index');
    }

    #[Route('/join/{activity}', name: 'join')]
    public function join(Activity $activity, ActivityRepository $activityRepository): Response
    {
        /**
         * @var Participant $currentParticipant
         */
        $currentParticipant = $this->getUser();
        $activity->addParticipant($currentParticipant);
        $activityRepository->add($activity, true);
        $this->addFlash("success","Inscription validée.");
        return $this->redirectToRoute('index');
    }
}
