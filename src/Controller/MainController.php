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

        if(!$searchForm->get('save')->isClicked()){
//            $stateOpened = $stateRepository->findOneBy(['wording' => 'Activity opened']);
//            $stateClosed = $stateRepository->findOneBy(['wording' => 'Activity closed']);
//            $stateEnded = $stateRepository->findOneBy(['wording' => 'Activity ended']);
//            $stateInProg = $stateRepository->findOneBy(['wording' => 'Activity in progress']);
//            $stateArch = $stateRepository->findOneBy(['wording' => 'Activity archived']);
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

                $dateBeginning = clone $activity->getDateTimeBeginning();
                $duration = $activity->getDuration();
                $dateEndActivity = $dateBeginning->modify($duration . 'minute');
                $dateArchive = $dateEndActivity->modify('+1 month');
                $dateNow = new \DateTime();

                if ($activity->getState()->getWording() == $stateClosed &&
                    (count($activity->getParticipants()) < $activity->getMaxNbRegistrations() ||
                    $activity->getDateLimitRegistration() > $dateNow) ){
                    $activity->setState($stateOpened);

                }
                elseif ($activity->getState()->getWording() == $stateOpened &&
                    (count($activity->getParticipants()) == $activity->getMaxNbRegistrations() ||
                    $activity->getDateLimitRegistration() < $dateNow) ){
                    $activity->setState($stateClosed);

                }

                elseif ($activity->getState()->getWording() == $stateClosed
                    && $dateNow < $dateEndActivity
                    && $dateBeginning < $dateNow){
                    $activity->setState($stateInProg);
                }
                elseif ($dateEndActivity < $dateNow && $activity->getState()->getWording() == $stateInProg){
                    $activity->setState($stateEnded);
                }

                elseif($dateArchive < $dateNow && ($activity->getState()->getWording() == $stateEnded || $activity->getState()->getWording() == $stateCancelled)){
                    $activity->setState($stateArch);

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

        $state = $stateRepository->findOneBy(['wording' => 'Activity opened']);
        $activity->setState($state);
        $activityRepository->add($activity, true);
        $this->addFlash("success","Sortie publiée.");
        return $this->redirectToRoute('index');
    }

}
