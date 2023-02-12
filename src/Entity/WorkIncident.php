<?php

namespace App\Entity;

use App\Repository\WorkIncidentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkIncidentRepository::class)
 */
class WorkIncident
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="workIncidents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adherent;

    /**
     * @ORM\ManyToOne(targetEntity=Establishment::class, inversedBy="workIncidents")
     */
    private $establishment;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class, inversedBy="workIncidents")
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $incidentPlace;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $source;

    // /**
    //  * @ORM\Column(type="string", length=255)
    //  */
    // private $reason;

    /**
     * @ORM\Column(type="integer")
     */
    private $dayOffNumber;

    /**
     * @ORM\ManyToOne(targetEntity=WorkIncidentLesion::class, inversedBy="workIncidents")
     */
    private $reason;

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

    public function getEstablishment(): ?Establishment
    {
        return $this->establishment;
    }

    public function setEstablishment(?Establishment $establishment): self
    {
        $this->establishment = $establishment;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getIncidentPlace(): ?string
    {
        return $this->incidentPlace;
    }

    public function setIncidentPlace(string $incidentPlace): self
    {
        $this->incidentPlace = $incidentPlace;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    // public function getReason(): ?string
    // {
    //     return $this->reason;
    // }

    // public function setReason(string $reason): self
    // {
    //     $this->reason = $reason;

    //     return $this;
    // }

    public function getDayOffNumber(): ?int
    {
        return $this->dayOffNumber;
    }

    public function setDayOffNumber(int $dayOffNumber): self
    {
        $this->dayOffNumber = $dayOffNumber;

        return $this;
    }

    public function getReason(): ?WorkIncidentLesion
    {
        return $this->reason;
    }

    public function setReason(?WorkIncidentLesion $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

}
