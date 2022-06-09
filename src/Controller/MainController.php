<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Participant;
use App\Form\SearchType;
use App\Repository\ActivityRepository;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Repository\StateRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Model\SearchActivityModel;


class MainController extends AbstractController
{



    #[Route('/', name: 'index')]
    public function index(Request $request,
                          ActivityRepository $activityRepository,
                          StateRepository $stateRepository, EntityManagerInterface $entityManager
                          ): Response
    {

        $isUser = $this->isGranted("ROLE_USER");
        if (!$isUser){
            return $this->redirectToRoute('app_login');
        }
        $currentParticipant = $this->getUser();


        $searchActivityModel = new SearchActivityModel();


        $searchForm = $this->createForm(SearchType::class, $searchActivityModel);
        $searchForm->handleRequest($request);

        if ($searchActivityModel->getMinDateTimeBeginning()){
            $searchActivityModel->getMinDateTimeBeginning()->modify('00:00:01');
        }

        if ($searchActivityModel->getMaxDateTimeBeginning()){
            $searchActivityModel->getMaxDateTimeBeginning()->modify('23:59:59');
        }



        if($searchForm->get('save')->isClicked()){

            $stateCreated = null;
            $stateOpened = null;
            $stateClosed = null;
            $stateEnded = null;
            $stateInProg = null;
            $stateArch = null;
            $states =$stateRepository->findAll();
            foreach ($states as $state){
                if ($state->getWording() == 'Activity created'){
                    $stateCreated = $state;
                }
                if ($state->getWording() == 'Activity opened'){
                    $stateOpened = $state;
                }
                if ($state->getWording() == 'Activity ended'){
                    $stateEnded = $state;
                }
                if ($state->getWording() == 'Activity in progress'){
                    $stateInProg = $state;
                }
                if ($state->getWording() == 'Activity closed'){
                    $stateClosed = $state;
                }
                if ($state->getWording() == 'Activity archived'){
                    $stateArch = $state;
                }
                if ($state->getWording() == 'Activity cancelled'){
                    $stateCancelled = $state;
                }


            }
            $activities = $activityRepository->findActivitiesAndStates();



            foreach ($activities as $activity){

                $dateBeginning = $activity->getDateTimeBeginning();
                $dateEndActivity = clone $activity->getDateTimeBeginning();
                $duration = $activity->getDuration();
                $dateEndActivity->modify('+' . $duration . 'minutes');
                $dateArchive =  clone $dateEndActivity;
                $dateArchive->modify('+1 month');
                $dateLimitReg = $activity->getDateLimitRegistration();

                $dateNow = new \DateTime('now');



                if ($activity->getState() == $stateClosed
                    && (count($activity->getParticipants()) < $activity->getMaxNbRegistrations()
                        && $dateLimitReg > $dateNow) ){
                    $activity->setState($stateOpened);
//                    if ($activity->getId() == 67){
//                        dump($activity);
//                    }
                }
                if ($activity->getState() == $stateOpened &&
                    (count($activity->getParticipants()) == $activity->getMaxNbRegistrations()
                        || $dateLimitReg < $dateNow) ){
                    $activity->setState($stateClosed);

                }

                if ( ($dateBeginning < $dateNow
                    && $dateNow < $dateEndActivity)
                    ){
                    $activity->setState($stateInProg);

                }
                if ($dateEndActivity < $dateNow
                    && $activity->getState() == $stateInProg){
                    $activity->setState($stateEnded);

                }

                if($dateArchive < $dateNow
                    && $activity->getState() == $stateEnded){
                    $activity->setState($stateArch);

                }
                if($dateArchive < $dateNow
                    && $activity->getState() == $stateCancelled){
                    $activity->setState($stateArch);

                }
                if($activity->getState() == $stateCreated
                    && $dateBeginning < $dateNow){
                    $activity->setState($stateEnded);

                }


                $activityRepository->add($activity, false);
            }
            $entityManager->flush();

        }

        if ($searchForm->isSubmitted() && $searchForm->isValid()){

            $lstActivities = $activityRepository->findByFilters($searchActivityModel, $currentParticipant, $stateRepository);


            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'searchForm' => $searchForm->createView(),
                'searchButton' => true,
                'lstActivities' => $lstActivities
            ]);
        }

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'searchForm' => $searchForm->createView(),
            'searchButton' => false,

        ]);


    }

    #[Route('/desist/{activity}', name: 'desist')]
    public function desist(Activity $activity, ActivityRepository $activityRepository, StateRepository $stateRepository): Response
    {
        /**
         * @var Participant $currentParticipant
         */
        $currentParticipant = $this->getUser();
        $activity->removeParticipant($currentParticipant);

        if (!in_array($currentParticipant, $activity->getParticipants())){
            throw $this->createAccessDeniedException('Vous ne faites pas partie des participants, vous ne pouvez pas vous désister.');
        }

        if (count($activity->getParticipants()) < $activity->getMaxNbRegistrations()){
            $stateOpened = $stateRepository->findOneBy(['wording' => 'Activity opened']);
            $activity->setState($stateOpened);
        }
        $activityRepository->add($activity, true);

        $this->addFlash("success","Désistement validé.");
        return $this->redirectToRoute('index');
    }

    #[Route('/join/{activity}', name: 'join')]
    public function join(Activity $activity, ActivityRepository $activityRepository, StateRepository $stateRepository): Response
    {
        /**
         * @var Participant $currentParticipant
         */
        $currentParticipant = $this->getUser();

        if($activity->getState()->getWording() == 'Activity closed'){
            throw $this->createAccessDeniedException('Vous ne pouvez pas vous inscrire');
        }

        $activity->addParticipant($currentParticipant);
        if (count($activity->getParticipants()) == $activity->getMaxNbRegistrations()){
            $stateClosed = $stateRepository->findOneBy(['wording' => 'Activity closed']);
            $activity->setState($stateClosed);
        }
        $activityRepository->add($activity, true);
        $this->addFlash("success","Inscription validée.");
        return $this->redirectToRoute('index');
    }

    #[Route('/publish/{activity}', name: 'publish')]
    public function publish(Activity $activity,
                            ActivityRepository $activityRepository,
                            StateRepository $stateRepository): Response
    {

        if ($activity->getOrganizer() != $this->getUser()){
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour cela.');
        }

        $state = $stateRepository->findOneBy(['wording' => 'Activity opened']);
        $activity->setState($state);
        $activityRepository->add($activity, true);
        $this->addFlash("success","Sortie publiée.");
        return $this->redirectToRoute('index');
    }

}
