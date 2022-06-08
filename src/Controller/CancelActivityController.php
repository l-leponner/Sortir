<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use App\Repository\StateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CancelActivityController extends AbstractController
{
    #[Route('/cancel/{activity}', name: 'cancel')]
    public function cancel(?Activity $activity, ActivityRepository $activityRepository, Request $request, StateRepository $stateRepository): Response
    {

//        $defaultData = ['message' => 'Type your message here'];
        $motiveForm = $this->createFormBuilder()
            ->add('motive', TextareaType::class, [
                'label' => 'Motif : ',
                'attr' => [
                    'placeholder' => 'Motif de l\'annulation'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Confirmer'
            ])
            ->getForm();

        $motiveForm->handleRequest($request);

        if ($motiveForm->isSubmitted() && $motiveForm->isValid()) {

            $motive = 'ANNULATION : ' . $motiveForm->get('motive')->getData();
            $activity->setInfosActivity($motive);
            $state = $stateRepository->findOneBy(['wording' => 'Activity cancelled']);
            $activity->setState($state);
            $activityRepository->add($activity, true);
            $this->redirectToRoute('index');
        }
        return $this->render('cancel_activity/cancel.html.twig', [
            'controller_name' => 'CancelActivityController',
            'motiveForm' => $motiveForm->createView(),
            'activity' => $activity
        ]);
    }
}
