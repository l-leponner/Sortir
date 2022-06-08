<?php

namespace App\Controller;
use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/participant', name: 'participant_')]
class ParticipantController extends AbstractController
{

    #[Route('/detail/{user}', name: 'detail')]
    public function profile(?Participant $user): Response
    {

        return $this->render('participant/detail.html.twig', [
            'user' =>$user
       ]);
    }

    #[Route('/profile', name: 'profile')]
    public function editProfil(Request $request,EntityManagerInterface $manager,
                               UserPasswordHasherInterface $hasherPassword, FileUploader $fileUploader): Response
    {


        $user = $this->getUser();

        $form = $this->createForm(ParticipantType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            if ($form->get('password')->getData()) {

                $hashPassword = $hasherPassword->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($hashPassword);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('succes', 'Mot de passe modifié avec succès.');
                return $this->redirectToRoute('participant_profile');
            }
//            ###################image#######################
//
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $user->setBrochureFilename($brochureFileName);
            }


             ###############################################

            $user = $form->getData();

            $manager->persist($user);

            $manager->flush();

            $this->addFlash('success', 'Profil modifié avec succès.');


        }

        return $this->render('participant/profile.html.twig', [
            'editProfilForm' => $form->createView(),
        ]);

    }
}


