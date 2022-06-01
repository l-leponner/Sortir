<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CancelActivityController extends AbstractController
{
    #[Route('/cancel', name: 'app_cancel_activity')]
    public function index(): Response
    {
        return $this->render('cancel_activity/cancel.html.twig', [
            'controller_name' => 'CancelActivityController',
        ]);
    }
}
