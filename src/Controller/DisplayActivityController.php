<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Participant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/displayActivity', name: 'displayActivity_')]
class DisplayActivityController extends AbstractController
{


    #[Route('/display/{activity}', name: 'display')]
    public function display(?Activity $activity, ?Participant $user): Response
    {
        return $this->render('activity/display_activity.html.twig', [
            'activity' => $activity,
            'user' =>$user
        ]);

    }

}
