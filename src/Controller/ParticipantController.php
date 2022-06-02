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


    #[Route('/detail', name: 'detail')]
    public function profile(ParticipantRepository $profile): Response
    {

       $user =$this->getUser();
       $myProfile = $profile -> find($user);
        return $this->render('participant/detail.html.twig', [

       ]);
    }
    #[Route('/profile', name: 'profile')]
    public function editProfil(Request $request,EntityManagerInterface $manager,   UserPasswordHasherInterface $hasherPassword ): Response
    {

        $user = $this->getUser();

        $form = $this->createForm(ParticipantType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){


            dump($form->get('password')->getData());

            if($form->get('password')->getData()){
//                  $user = $form->getData();
                dump('ici');
                $hashPassword = $hasherPassword->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($hashPassword);
                $manager->persist($user);
                $manager ->flush();
                $this->addFlash('success', 'Mot de passe modifié avec succès.');
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




//    public function edit( ParticipantRepository $repo, Request $request): Response
//    {
//       $user =$this->getUser();
////        if($em !== $participant){
////            return $this ->redirectToRoute("app_login");
////        }
//
//
//        $profileForm = $this ->createForm(ParticipantType::class, $user);
//        $profileForm->handleRequest($request);
//
//        if($profileForm->isSubmitted()&&$profileForm->isValid()){
////            $user =$profileForm ->getData();
////            $manager ->persist($user);
////            $manager ->flush();
//            $repo ->add($user, true);
//            $this -> addFlash("success", "Profil modifié avec succès !");
//
//            return $this ->redirectToRoute('index');
//        }
//
//
//
//        return $this ->render('participant/profile.html.twig', [
//            'profilForm' => $profileForm->createView()
//        ]);
//
//    }


}
