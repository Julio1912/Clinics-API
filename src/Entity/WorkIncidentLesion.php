<?php

namespace App\Entity;

use App\Repository\WorkIncidentLesionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkIncidentLesionRepository::class)
 */
class WorkIncidentLesion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=WorkIncident::class, mappedBy="reason")
     */
    private $workIncidents;

    public function __construct()
    {
        $this->workIncidents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|WorkIncident[]
     */
    public function getWorkIncidents(): Collection
    {
        return $this->workIncidents;
    }

    public function addWorkIncident(WorkIncident $workIncident): self
    {
        if (!$this->workIncidents->contains($workIncident)) {
            $this->workIncidents[] = $workIncident;
            $workIncident->setReason($this);
        }

        return $this;
    }

    public function removeWorkIncident(WorkIncident $workIncident): self
    {
        if ($this->workIncidents->removeElement($workIncident)) {
            // set the owning side to null (unless already changed)
            if ($workIncident->getReason() === $this) {
                $workIncident->setReason(null);
            }
        }

        return $this;
    }
}
