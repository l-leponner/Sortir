<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[Assert\Length(max: 100,
        maxMessage: 'Le nom de peut dépasser 100 caractères.')]
    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide.')]
    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[Assert\Length(max: 200,
        maxMessage: 'La rue de peut dépasser 100 caractères.')]
    #[Assert\NotBlank(message: 'La rue ne peut pas être vide.')]
    #[ORM\Column(type: 'string', length: 200)]
    private $street;

    #[Assert\Type('float', message: 'La latitude doit être un nombre décimal.')]
    #[ORM\Column(type: 'float', nullable: true)]
    private $latitude;

    #[Assert\Type('float', message: 'La longitude doit être un nombre décimal.')]
    #[ORM\Column(type: 'float', nullable: true)]
    private $longitude;

    #[ORM\OneToMany(mappedBy: 'place', targetEntity: Activity::class)]
    private $activities;

    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'places')]
    #[ORM\JoinColumn(nullable: false)]
    private $city;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setPlace($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getPlace() === $this) {
                $activity->setPlace(null);
            }
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
