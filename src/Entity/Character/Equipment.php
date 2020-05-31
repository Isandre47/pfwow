<?php

namespace App\Entity\Character;

use App\Repository\Character\EquipmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipmentRepository::class)
 */
class Equipment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $head;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cloak;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $neck;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $shoulder;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $chest;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wrist;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hands;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $waist;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $legs;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $feet;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $finger_1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $finger_2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $trinket_1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $trinket_2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $main_hand;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $off_hand;

    /**
     * @ORM\OneToOne(targetEntity=Profile::class, inversedBy="equipment", cascade={"persist", "remove"})
     */
    private $profil;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHead(): ?int
    {
        return $this->head;
    }

    public function setHead(?int $head): self
    {
        $this->head = $head;

        return $this;
    }

    public function getCloak(): ?int
    {
        return $this->cloak;
    }

    public function setCloak(?int $cloak): self
    {
        $this->cloak = $cloak;

        return $this;
    }

    public function getNeck(): ?int
    {
        return $this->neck;
    }

    public function setNeck(?int $neck): self
    {
        $this->neck = $neck;

        return $this;
    }

    public function getShoulder(): ?int
    {
        return $this->shoulder;
    }

    public function setShoulder(?int $shoulder): self
    {
        $this->shoulder = $shoulder;

        return $this;
    }

    public function getChest(): ?int
    {
        return $this->chest;
    }

    public function setChest(?int $chest): self
    {
        $this->chest = $chest;

        return $this;
    }

    public function getWrist(): ?int
    {
        return $this->wrist;
    }

    public function setWrist(?int $wrist): self
    {
        $this->wrist = $wrist;

        return $this;
    }

    public function getHands(): ?int
    {
        return $this->hands;
    }

    public function setHands(?int $hands): self
    {
        $this->hands = $hands;

        return $this;
    }

    public function getWaist(): ?int
    {
        return $this->waist;
    }

    public function setWaist(?int $waist): self
    {
        $this->waist = $waist;

        return $this;
    }

    public function getLegs(): ?int
    {
        return $this->legs;
    }

    public function setLegs(?int $legs): self
    {
        $this->legs = $legs;

        return $this;
    }

    public function getFeet(): ?int
    {
        return $this->feet;
    }

    public function setFeet(?int $feet): self
    {
        $this->feet = $feet;

        return $this;
    }

    public function getFinger1(): ?int
    {
        return $this->finger_1;
    }

    public function setFinger1(?int $finger_1): self
    {
        $this->finger_1 = $finger_1;

        return $this;
    }

    public function getFinger2(): ?int
    {
        return $this->finger_2;
    }

    public function setFinger2(?int $finger_2): self
    {
        $this->finger_2 = $finger_2;

        return $this;
    }

    public function getTrinket1(): ?int
    {
        return $this->trinket_1;
    }

    public function setTrinket1(?int $trinket_1): self
    {
        $this->trinket_1 = $trinket_1;

        return $this;
    }

    public function getTrinket2(): ?int
    {
        return $this->trinket_2;
    }

    public function setTrinket2(?int $trinket_2): self
    {
        $this->trinket_2 = $trinket_2;

        return $this;
    }

    public function getMainHand(): ?int
    {
        return $this->main_hand;
    }

    public function setMainHand(?int $main_hand): self
    {
        $this->main_hand = $main_hand;

        return $this;
    }

    public function getOffHand(): ?int
    {
        return $this->off_hand;
    }

    public function setOffHand(?int $off_hand): self
    {
        $this->off_hand = $off_hand;

        return $this;
    }

    public function getProfil(): ?Profile
    {
        return $this->profil;
    }

    public function setProfil(?Profile $profil): self
    {
        $this->profil = $profil;

        return $this;
    }
}
