<?php

namespace App\Controller;

use App\Form\ActivityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    #[Route('/activity', name: 'app_activity')]
    public function index(): Response
    {
        $form = $this->createForm(ActivityType::class);

        return $this->render('activity/activity.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
