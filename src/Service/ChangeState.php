<?php

namespace App\Service;

use App\Entity\Activity;
use App\Entity\State;
use App\Repository\ActivityRepository;
use App\Repository\StateRepository;

class ChangeState
{

    private $dateOpened;
    private $dateEnded;
    private $dateClosed;
    private $dateInProgress;
    private $dateArchived;
    private Activity $activity;
    private State $state;

    private StateRepository $stateRepository;
    private ActivityRepository $activityRepository;

    public function __construct($stateRepository, $activityRepository)
    {
        $this->stateRepository = $stateRepository;
        $this->activityRepository = $activityRepository;
    }

    /**
     * @return mixed
     */
    public function getDateOpened()
    {
        return $this->dateOpened;
    }

    /**
     * @param mixed $dateOpened
     * @return ChangeState
     */
    public function setDateOpened($dateOpened)
    {
        $this->dateOpened = $dateOpened;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateEnded()
    {
        return $this->dateEnded;
    }

    /**
     * @param mixed $dateEnded
     * @return ChangeState
     */
    public function setDateEnded($dateEnded)
    {
        $this->dateEnded = $dateEnded;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateClosed()
    {
        return $this->dateClosed;
    }

    /**
     * @param mixed $dateClosed
     * @return ChangeState
     */
    public function setDateClosed($dateClosed)
    {
        $this->dateClosed = $dateClosed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateInProgress()
    {
        return $this->dateInProgress;
    }

    /**
     * @param mixed $dateInProgress
     * @return ChangeState
     */
    public function setDateInProgress($dateInProgress)
    {
        $this->dateInProgress = $dateInProgress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateArchived()
    {
        return $this->dateArchived;
    }

    /**
     * @param mixed $dateArchived
     * @return ChangeState
     */
    public function setDateArchived($dateArchived)
    {
        $this->dateArchived = $dateArchived;
        return $this;
    }

    /**
     * @return Activity
     */
    public function getActivity(): Activity
    {
        return $this->activity;
    }

    /**
     * @param Activity $activity
     * @return ChangeState
     */
    public function setActivity(Activity $activity): ChangeState
    {
        $this->activity = $activity;
        return $this;
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }

    /**
     * @param State $state
     * @return ChangeState
     */
    public function setState(State $state): ChangeState
    {
        $this->state = $state;
        return $this;
    }




    public function isOpenedOrClosed(Activity $activity){
        if (count($activity->getParticipants()) < $activity->getMaxNbRegistrations() &&
            $activity->getDateLimitRegistration() > new \DateTime()){
            $activity->setState($this->stateRepository->findOneBy(['wording' => 'Activity opened']));
            $this->activityRepository->add($activity, true);
        } else {
            $activity->setState($this->stateRepository->findOneBy(['wording' => 'Activity closed']));
            $this->activityRepository->add($activity, true);
        }
    }

    public function isEnded(Activity $activity){
        if($activity->getDateTimeBeginning()->modify('+' . $activity->getDuration() . 'minute') < new \DateTime()){
            $activity->setState($this->stateRepository->findOneBy(['wording' => 'Activity ended']));
            $this->activityRepository->add($activity, true);
        } elseif ($activity->getDateTimeBeginning()->modify('+' . $activity->getDuration() . 'minutes') > new \DateTime() &&
            $activity->getDateTimeBeginning() < new \DateTime()){
            $activity->setState($this->stateRepository->findOneBy(['wording' => 'Activity in progress']));
            $this->activityRepository->add($activity, true);
        }
    }

    public function isArchived(Activity $activity){
        if($activity->getDateTimeBeginning()->modify('+' . $activity->getDuration() . 'minute')->modify('+1 month') < new \DateTime()){
            $activity->setState($this->stateRepository->findOneBy(['wording' => 'Activity archived']));
            $this->activityRepository->add($activity, true);
        }
    }
}