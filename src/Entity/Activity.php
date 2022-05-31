<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\Column(type: 'datetime')]
    private $dateTimeBeginning;

    #[ORM\Column(type: 'integer')]
    private $duration;

    #[ORM\Column(type: 'date')]
    private $dateLimitRegistration;

    #[ORM\Column(type: 'integer')]
    private $maxNbRegistrations;

    #[ORM\Column(type: 'text')]
    private $infosActivity;

    #[ORM\ManyToMany(targetEntity: Participant::class, mappedBy: 'joinedActivities')]
    private $participants;

    #[ORM\ManyToOne(targetEntity: Participant::class, inversedBy: 'createdActivities')]
    #[ORM\JoinColumn(nullable: false)]
    private $organizer;

    #[ORM\ManyToOne(targetEntity: Campus::class, inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private $campus;

    #[ORM\ManyToOne(targetEntity: Place::class, inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private $place;

    #[ORM\ManyToOne(targetEntity: State::class, inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private $state;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateTimeBeginning(): ?\DateTimeInterface
    {
        return $this->dateTimeBeginning;
    }

    public function setDateTimeBeginning(\DateTimeInterface $dateTimeBeginning): self
    {
        $this->dateTimeBeginning = $dateTimeBeginning;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDateLimitRegistration(): ?\DateTimeInterface
    {
        return $this->dateLimitRegistration;
    }

    public function setDateLimitRegistration(\DateTimeInterface $dateLimitRegistration): self
    {
        $this->dateLimitRegistration = $dateLimitRegistration;

        return $this;
    }

    public function getMaxNbRegistrations(): ?int
    {
        return $this->maxNbRegistrations;
    }

    public function setMaxNbRegistrations(int $maxNbRegistrations): self
    {
        $this->maxNbRegistrations = $maxNbRegistrations;

        return $this;
    }

    public function getInfosActivity(): ?string
    {
        return $this->infosActivity;
    }

    public function setInfosActivity(string $infosActivity): self
    {
        $this->infosActivity = $infosActivity;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->addJoinedActivity($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeJoinedActivity($this);
        }

        return $this;
    }

    public function getOrganizer(): ?Participant
    {
        return $this->organizer;
    }

    public function setOrganizer(?Participant $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }
}
