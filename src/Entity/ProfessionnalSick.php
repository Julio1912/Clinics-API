<?php

namespace App\Entity;

use App\Repository\ProfessionnalSickRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfessionnalSickRepository::class)
 */
class ProfessionnalSick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="professionnalSicks")
     */
    private $adherent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $characteristic;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $circumstance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $agent;

    /**
     * @ORM\Column(type="integer")
     */
    private $dayOffNumber;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }

    public function getCharacteristic(): ?string
    {
        return $this->characteristic;
    }

    public function setCharacteristic(string $characteristic): self
    {
        $this->characteristic = $characteristic;

        return $this;
    }

    public function getCircumstance(): ?string
    {
        return $this->circumstance;
    }

    public function setCircumstance(string $circumstance): self
    {
        $this->circumstance = $circumstance;

        return $this;
    }

    public function getAgent(): ?string
    {
        return $this->agent;
    }

    public function setAgent(string $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getDayOffNumber(): ?int
    {
        return $this->dayOffNumber;
    }

    public function setDayOffNumber(int $dayOffNumber): self
    {
        $this->dayOffNumber = $dayOffNumber;

        return $this;
    }

}
