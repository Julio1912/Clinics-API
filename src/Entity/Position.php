<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PositionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PositionRepository::class)
 * @UniqueEntity(
 * fields = {"name"},
 * message = "Cette poste existe déjà! "
 * )
 */
class Position
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
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $code;

    /**
     * @ORM\ManyToMany(targetEntity=Establishment::class, mappedBy="position")
     */
    private $establishments;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="position")
     */
    private $adherents;

    /**
     * @ORM\OneToMany(targetEntity=Worker::class, mappedBy="position")
     */
    private $workers;

    /**
     * @ORM\OneToMany(targetEntity=Family::class, mappedBy="position")
     */
    private $families;

    /**
     * @ORM\OneToMany(targetEntity=WorkIncident::class, mappedBy="position")
     */
    private $workIncidents;

    public function __construct()
    {
        $this->establishments = new ArrayCollection();
        $this->adherents = new ArrayCollection();
        $this->workers = new ArrayCollection();
        $this->families = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Establishment[]
     */
    public function getEstablishments(): Collection
    {
        return $this->establishments;
    }

    public function addEstablishment(Establishment $establishment): self
    {
        if (!$this->establishments->contains($establishment)) {
            $this->establishments[] = $establishment;
            $establishment->addPosition($this);
        }

        return $this;
    }

    public function removeEstablishment(Establishment $establishment): self
    {
        if ($this->establishments->removeElement($establishment)) {
            $establishment->removePosition($this);
        }

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
     * @return Collection|Adherent[]
     */
    public function getAdherents(): Collection
    {
        return $this->adherents;
    }

    public function addAdherent(Adherent $adherent): self
    {
        if (!$this->adherents->contains($adherent)) {
            $this->adherents[] = $adherent;
            $adherent->setPosition($this);
        }

        return $this;
    }

    public function removeAdherent(Adherent $adherent): self
    {
        if ($this->adherents->removeElement($adherent)) {
            // set the owning side to null (unless already changed)
            if ($adherent->getPosition() === $this) {
                $adherent->setPosition(null);
            }
        }

        return $this;
    }
    
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Worker[]
     */
    public function getWorkers(): Collection
    {
        return $this->workers;
    }

    public function addWorker(Worker $worker): self
    {
        if (!$this->workers->contains($worker)) {
            $this->workers[] = $worker;
            $worker->setPosition($this);
        }

        return $this;
    }

    public function removeWorker(Worker $worker): self
    {
        if ($this->workers->removeElement($worker)) {
            // set the owning side to null (unless already changed)
            if ($worker->getPosition() === $this) {
                $worker->setPosition(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Family[]
     */
    public function getFamilies(): Collection
    {
        return $this->families;
    }

    public function addFamily(Family $family): self
    {
        if (!$this->families->contains($family)) {
            $this->families[] = $family;
            $family->setPosition($this);
        }

        return $this;
    }

    public function removeFamily(Family $family): self
    {
        if ($this->families->removeElement($family)) {
            // set the owning side to null (unless already changed)
            if ($family->getPosition() === $this) {
                $family->setPosition(null);
            }
        }

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
            $workIncident->setPosition($this);
        }

        return $this;
    }

    public function removeWorkIncident(WorkIncident $workIncident): self
    {
        if ($this->workIncidents->removeElement($workIncident)) {
            // set the owning side to null (unless already changed)
            if ($workIncident->getPosition() === $this) {
                $workIncident->setPosition(null);
            }
        }

        return $this;
    }
}
