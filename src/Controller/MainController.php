<?php

namespace App\Controller;

use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {

        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()){

        }

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'searchForm' => $searchForm->createView(),
        ]);
    }
}
