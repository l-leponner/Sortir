<?php

namespace App\Form\Model;

use App\Entity\Campus;

use App\Entity\Participant;
use Symfony\Component\Validator\Constraints as Assert;
class SearchActivityModel
{

    private ?Campus $participantCampus = null;

    private ?string $nameKeyword = null;

//    #[Assert\LessThan($maxDateTimeBeginning->)]
    private ?\DateTimeInterface $minDateTimeBeginning = null;
//    #[Assert\GreaterThanOrEqual($minDateTimeBeginning)]
    private ?\DateTimeInterface $maxDateTimeBeginning = null;

    private bool $filterActiOrganized = false;

    private bool $filterActiJoined = false;

    private bool $filterActiNotJoined = false;

    private bool $filterActiEnded = false;



    /**
     * @return Campus
     */
    public function getParticipantCampus(): ?Campus
    {
        return $this->participantCampus;
    }

    /**
     * @param Campus $participantCampus
     * @return SearchActivityModel
     */
    public function setParticipantCampus(Campus $participantCampus): SearchActivityModel
    {
        $this->participantCampus = $participantCampus;
        return $this;
    }


    /**
     * @return string
     */
    public function getNameKeyword(): ?string
    {
        return $this->nameKeyword;
    }

    /**
     * @param string $nameKeyword
     * @return SearchActivityModel
     */
    public function setNameKeyword(string $nameKeyword): SearchActivityModel
    {
        $this->nameKeyword = $nameKeyword;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getMinDateTimeBeginning(): ?\DateTimeInterface
    {
        return $this->minDateTimeBeginning;
    }

    /**
     * @param DateTimeInterface|null $minDateTimeBeginning
     * @return SearchActivityModel
     */
    public function setMinDateTimeBeginning(?\DateTimeInterface $minDateTimeBeginning): SearchActivityModel
    {
        $this->minDateTimeBeginning = $minDateTimeBeginning;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getMaxDateTimeBeginning(): ?\DateTimeInterface
    {
        return $this->maxDateTimeBeginning;
    }


    public function setMaxDateTimeBeginning(?\DateTimeInterface $maxDateTimeBeginning): SearchActivityModel
    {
        $this->maxDateTimeBeginning = $maxDateTimeBeginning;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFilterActiOrganized(): bool
    {
        return $this->filterActiOrganized;
    }

    /**
     * @param bool $filterActiOrganized
     * @return SearchActivityModel
     */
    public function setFilterActiOrganized(bool $filterActiOrganized): SearchActivityModel
    {
        $this->filterActiOrganized = $filterActiOrganized;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFilterActiJoined(): bool
    {
        return $this->filterActiJoined;
    }

    /**
     * @param bool $filterActiJoined
     * @return SearchActivityModel
     */
    public function setFilterActiJoined(bool $filterActiJoined): SearchActivityModel
    {
        $this->filterActiJoined = $filterActiJoined;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFilterActiNotJoined(): bool
    {
        return $this->filterActiNotJoined;
    }

    /**
     * @param bool $filterActiNotJoined
     * @return SearchActivityModel
     */
    public function setFilterActiNotJoined(bool $filterActiNotJoined): SearchActivityModel
    {
        $this->filterActiNotJoined = $filterActiNotJoined;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFilterActiEnded(): bool
    {
        return $this->filterActiEnded;
    }

    /**
     * @param bool $filterActiEnded
     * @return SearchActivityModel
     */
    public function setFilterActiEnded(bool $filterActiEnded): SearchActivityModel
    {
        $this->filterActiEnded = $filterActiEnded;
        return $this;
    }



}