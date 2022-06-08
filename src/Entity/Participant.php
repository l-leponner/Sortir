<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\Column(type: 'string', length: 100)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 20)]
    private $phone;

    #[ORM\Column(type: 'boolean')]
    private $active;

    #[ORM\ManyToMany(targetEntity: Activity::class, inversedBy: 'participants')]
    private $joinedActivities;

    #[ORM\OneToMany(mappedBy: 'organizer', targetEntity: Activity::class)]
    private $createdActivities;

    #[ORM\ManyToOne(targetEntity: Campus::class, inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private $campus;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private $username;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $brochureFilename;

    public function __construct()
    {
        $this->joinedActivities = new ArrayCollection();
        $this->createdActivities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getJoinedActivities(): Collection
    {
        return $this->joinedActivities;
    }

    public function addJoinedActivity(Activity $joinedActivity): self
    {
        if (!$this->joinedActivities->contains($joinedActivity)) {
            $this->joinedActivities[] = $joinedActivity;
        }

        return $this;
    }

    public function removeJoinedActivity(Activity $joinedActivity): self
    {
        $this->joinedActivities->removeElement($joinedActivity);

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getCreatedActivities(): Collection
    {
        return $this->createdActivities;
    }

    public function addCreatedActivity(Activity $createdActivity): self
    {
        if (!$this->createdActivities->contains($createdActivity)) {
            $this->createdActivities[] = $createdActivity;
            $createdActivity->setOrganizer($this);
        }

        return $this;
    }

    public function removeCreatedActivity(Activity $createdActivity): self
    {
        if ($this->createdActivities->removeElement($createdActivity)) {
            // set the owning side to null (unless already changed)
            if ($createdActivity->getOrganizer() === $this) {
                $createdActivity->setOrganizer(null);
            }
        }

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

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

//    public function getImage(): ?string
//    {
//        return $this->image;
//    }
//
//    public function setImage(?string $image): self
//    {
//        $this->image = $image;
//
//        return $this;
//    }
//    /**
//     * @ORM\Column(type="string")
//     */
//    private $brochureFilename;

    public function getBrochureFilename()
    {
        return $this->brochureFilename;
    }

    public function setBrochureFilename($brochureFilename)
    {
        $this->brochureFilename = $brochureFilename;

        return $this;
    }
}
