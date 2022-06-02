<?php

namespace App\Controller;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{


    #[Route('/detail/{id}', name: 'detail')]
    public function profile( $id,ParticipantRepository $profile): Response
    {

       $user =$profile->find($id);

        return $this->render('participant/detail.html.twig', [
        'id' =>$id,
         'user' =>$user
       ]);
    }
    #[Route('/profile', name: 'profile')]
    public function editProfil(Request $request,EntityManagerInterface $manager,   UserPasswordHasherInterface $hasherPassword ): Response
    {

        $user = $this->getUser();

        $form = $this->createForm(ParticipantType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){




            if($form->get('password')->getData()){


                $hashPassword = $hasherPassword->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($hashPassword);
                $manager->persist($user);
                $manager ->flush();
                $this->addFlash('succes', 'Mot de passe modifié avec succès.');
                return $this->redirectToRoute('participant_profile');
            }


            $user = $form->getData();
            $manager->persist($user);
            $manager ->flush();
            $this->addFlash('success', 'Profil modifié avec succès.');
            return $this->redirectToRoute('participant_profile');
        }


        return $this->render('participant/profile.html.twig', [
            'editProfilForm' => $form->createView()
        ]);
    }


}
