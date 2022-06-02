<?php

namespace App\Controller;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    #[Route('/myProfile', name: 'myProfile')]
    public function profile(ParticipantRepository $profile): Response
    {
//        $this ->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
//
       $user =$this->getUser();
//
//      return new Response($user -> getUserIdentifier());
//        $myProfile = $profile -> find($user);
        return $this->render('participant/profile.html.twig', [
//            'app.user' => $this,
//            'myProfile' => $myProfile
        ]);
    }
    #[Route('/profile', name: 'profile')]
    public function edit(ParticipantRepository $part, Request $request): Response
    {
        $user =$this->getUser();
        $myProfile =$part ->find($user);
        $profileForm = $this ->createForm(ParticipantType::class, $myProfile);
        $profileForm->handleRequest($request);

        if($profileForm->isSubmitted()&&$profileForm->isValid()){

            $part ->add($myProfile, true);
            $this -> addFlash("success", "Profil modifié avec succès !");
            return $this -> redirectToRoute("index");
        }
        return $this ->render('participant/detail.html.twig', [
            'profilForm' => $profileForm->createView()
        ]);











    }
//    $form =$this ->createFormBuilder()
//        ->add('Pseudo', TextType::class,[
//            'label' => 'Pseudo : '
//        ])
//        ->add('Prénom', TextType::class,[
//            'label' => 'Pseudo : '
//        ])
//        ->add('Nom', TextType::class,[
//            'label' => 'Nom : '
//        ])
//        ->add('Téléphone', Integer::class,[
//            'label' => 'Téléphone : '
//        ])
//        ->add('Email', TextType::class,[
//            'label' => 'Email : '
//        ])
//        ->getForm();
//        return $this ->render('participant/detail.html.twig',[
//            'form' => $form ->createView()
//        ]);
//    }

}
