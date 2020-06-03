<?php

namespace App\Entity\Character;

use App\Entity\Blizzard\Classe;
use App\Entity\Blizzard\Realm;
use App\Entity\User;
use App\Repository\Character\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="profile",uniqueConstraints={@ORM\UniqueConstraint(name="unique_profile", columns={"name", "realm_id"})})
 */
class Profile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $blizzard_character_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $faction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $race;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="profiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $character_class;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_spec;

    /**
     * @ORM\ManyToOne(targetEntity=Realm::class, inversedBy="profiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $realm;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $guild;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="integer")
     */
    private $achievement_points;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $media;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_login_timestamp;

    /**
     * @ORM\Column(type="integer")
     */
    private $equipped_item_level;

    /**
     * @ORM\OneToOne(targetEntity=Equipment::class, mappedBy="profil", cascade={"persist", "remove"})
     */
    private $equipment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="profiles")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlizzardCharacterId(): ?int
    {
        return $this->blizzard_character_id;
    }

    public function setBlizzardCharacterId(int $blizzard_character_id): self
    {
        $this->blizzard_character_id = $blizzard_character_id;

        return $this;
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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getFaction(): ?string
    {
        return $this->faction;
    }

    public function setFaction(string $faction): self
    {
        $this->faction = $faction;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(string $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getCharacterClass(): ?Classe
    {
        return $this->character_class;
    }

    public function setCharacterClass(?Classe $character_class): self
    {
        $this->character_class = $character_class;

        return $this;
    }

    public function getActiveSpec(): ?string
    {
        return $this->active_spec;
    }

    public function setActiveSpec(string $active_spec): self
    {
        $this->active_spec = $active_spec;

        return $this;
    }

    public function getRealm(): ?Realm
    {
        return $this->realm;
    }

    public function setRealm(?Realm $realm): self
    {
        $this->realm = $realm;

        return $this;
    }

    public function getGuild(): ?string
    {
        return $this->guild;
    }

    public function setGuild(string $guild): self
    {
        $this->guild = $guild;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getAchievementPoints(): ?int
    {
        return $this->achievement_points;
    }

    public function setAchievementPoints(int $achievement_points): self
    {
        $this->achievement_points = $achievement_points;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(string $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getLastLoginTimestamp(): ?\DateTimeInterface
    {
        return $this->last_login_timestamp;
    }

    public function setLastLoginTimestamp(\DateTimeInterface $last_login_timestamp): self
    {
        $this->last_login_timestamp = $last_login_timestamp;

        return $this;
    }

    public function getEquippedItemLevel(): ?int
    {
        return $this->equipped_item_level;
    }

    public function setEquippedItemLevel(int $equipped_item_level): self
    {
        $this->equipped_item_level = $equipped_item_level;

        return $this;
    }

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipment $equipment): self
    {
        $this->equipment = $equipment;

        // set (or unset) the owning side of the relation if necessary
        $newProfil = null === $equipment ? null : $this;
        if ($equipment->getProfil() !== $newProfil) {
            $equipment->setProfil($newProfil);
        }

        return $this;
    }

    public function setProfile($response)
    {
        foreach (get_object_vars($this) as $item => $key) {
            if (property_exists($response, $item) && $item != 'realm') {
                if (isset($response->{$item}->name)) {
                    $this->{$item} = $response->{$item}->name;
                } else {
                    $this->{$item} = $response->{$item};
                }
            }
        }
        $this->equipment = null;
        $this->id = null;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface $updatedAt
     * @return $this
     * @ORM\PrePersist()
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
