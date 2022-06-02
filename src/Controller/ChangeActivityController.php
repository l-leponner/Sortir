<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangeActivityController extends AbstractController
{
    #[Route('/change_activity', name: 'change_activity')]
    public function changeActivity(): Response
    {
        return $this->render('change_activity/change_activity.html.twig', [
            'controller_name' => 'ChangeActivityController',
        ]);
    }
}
