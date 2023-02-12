<?php

namespace App\Entity;

use App\Repository\EstablishmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EstablishmentRepository::class)
 */
class Establishment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $code;

    /**
     * @ORM\ManyToMany(targetEntity=Position::class, inversedBy="establishments")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="establishment")
     */
    private $adherents;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Worker::class, mappedBy="establishment")
     */
    private $workers;

    /**
     * @ORM\OneToMany(targetEntity=Family::class, mappedBy="establishment")
     */
    private $families;

    /**
     * @ORM\OneToMany(targetEntity=WorkIncident::class, mappedBy="establishment")
     */
    private $workIncidents;

    /**
     * @ORM\OneToMany(targetEntity=Consultation::class, mappedBy="establishment")
     */
    private $consultations;

    public function __construct()
    {
        $this->position = new ArrayCollection();
        $this->adherents = new ArrayCollection();
        $this->workers = new ArrayCollection();
        $this->families = new ArrayCollection();
        $this->workIncidents = new ArrayCollection();
        $this->consultations = new ArrayCollection();
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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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
     * @return Collection|Position[]
     */
    public function getPosition(): Collection
    {
        return $this->position;
    }

    public function addPosition(Position $position): self
    {
        if (!$this->position->contains($position)) {
            $this->position[] = $position;
        }

        return $this;
    }

    public function removePosition(Position $position): self
    {
        $this->position->removeElement($position);

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
            $adherent->setEstablishment($this);
        }

        return $this;
    }

    public function removeAdherent(Adherent $adherent): self
    {
        if ($this->adherents->removeElement($adherent)) {
            // set the owning side to null (unless already changed)
            if ($adherent->getEstablishment() === $this) {
                $adherent->setEstablishment(null);
            }
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
            $worker->setEstablishment($this);
        }

        return $this;
    }

    public function removeWorker(Worker $worker): self
    {
        if ($this->workers->removeElement($worker)) {
            // set the owning side to null (unless already changed)
            if ($worker->getEstablishment() === $this) {
                $worker->setEstablishment(null);
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
            $family->setEstablishment($this);
        }

        return $this;
    }

    public function removeFamily(Family $family): self
    {
        if ($this->families->removeElement($family)) {
            // set the owning side to null (unless already changed)
            if ($family->getEstablishment() === $this) {
                $family->setEstablishment(null);
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
            $workIncident->setEstablishment($this);
        }

        return $this;
    }

    public function removeWorkIncident(WorkIncident $workIncident): self
    {
        if ($this->workIncidents->removeElement($workIncident)) {
            // set the owning side to null (unless already changed)
            if ($workIncident->getEstablishment() === $this) {
                $workIncident->setEstablishment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Consultation[]
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): self
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations[] = $consultation;
            $consultation->setEstablishment($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getEstablishment() === $this) {
                $consultation->setEstablishment(null);
            }
        }

        return $this;
    }
}
