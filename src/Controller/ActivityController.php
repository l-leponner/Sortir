<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Participant;
use App\Form\ActivityType;
use App\Form\ChangeActivityType;
use App\Repository\ActivityRepository;
use App\Repository\StateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    #[Route('/activity', name: 'activity')]
    public function add(Request $request, StateRepository $stateRepository, ActivityRepository $activityRepository): Response
    {

        $activity = new Activity();

        /**
         * @var Participant $currentParticipant
         */
        $currentParticipant = $this->getUser();

        $addActivityForm = $this->createForm(ActivityType::class, $activity);
        $addActivityForm->handleRequest($request);
        if ($addActivityForm->isSubmitted() && $addActivityForm->isValid()){

            $activity->setOrganizer($currentParticipant);
            $activity->addParticipant($currentParticipant);

            if ($addActivityForm->get('save')->isClicked()){
                $state = $stateRepository->findOneBy(['wording' => 'Activity created']);
                $activity->setState($state);
                $activityRepository->add($activity, true);
                $this->addFlash("success", "Sortie enregistrée.");

            }

            if ($addActivityForm->get('saveAndPublish')->isClicked()){
                $state = $stateRepository->findOneBy(['wording' => 'Activity opened']);
                $activity->setState($state);
                $activityRepository->add($activity, true);
                $this->addFlash("success", "Sortie enregistrée et publiée.");
            }
            return $this->redirectToRoute('index');
        }

        return $this->render('activity/activity.html.twig', [
            'controller_name' => 'ActivityController',
            'addActivityForm' => $addActivityForm->createView(),
            'activity' => $activity
        ]);
    }
}
