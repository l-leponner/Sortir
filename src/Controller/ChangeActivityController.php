<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ChangeActivityType;
use App\Repository\ActivityRepository;
use App\Repository\StateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/change_activity', name: 'change_activity_')]
class ChangeActivityController extends AbstractController
{
    #[Route('/edit/{activity}', name: 'edit')]
    public function display(?Activity $activity, Request $request, StateRepository $stateRepository, ActivityRepository $activityRepository): Response
    {

        if ($activity->getOrganizer() != $this->getUser()){
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour cela.');
        }
//        $user=$this->getUser();
        $changeActivityForm = $this->createForm(ChangeActivityType::class, $activity);
        $changeActivityForm->handleRequest($request);
        if ($changeActivityForm->isSubmitted() && $changeActivityForm->isValid()){

            if ($changeActivityForm->get('save')->isClicked()){
                $activityRepository->add($activity, true);
                $this->addFlash("success", "Changements enregistrés.");

            }

            if ($changeActivityForm->get('saveAndPublish')->isClicked()){
                $state = $stateRepository->findOneBy(['wording' => 'Activity opened']);
                $activity->setState($state);
                $activityRepository->add($activity, true);
                $this->addFlash("success", "Changements enregistrés et sortie publiée.");
            }
            return $this->redirectToRoute('index');
        }

        return $this->render('change_activity/change_activity.html.twig', [
            'controller_name' => 'ChangeActivityController',
            'changeActivityForm' => $changeActivityForm->createView(),
            'activity' => $activity
        ]);
    }

    #[Route('/delete/{activity}', name: 'delete')]
    public function delete(?Activity $activity, ActivityRepository $activityRepository): Response
    {
            $activityRepository->remove($activity, true);
            $this->addFlash("success", "Sortie supprimée.");

            return $this->redirectToRoute('index');

    }

}
