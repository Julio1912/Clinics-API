<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ConsultationRepository::class)
 */
class Consultation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"consultation:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="consultations")
     * @Groups({"consultation:read"})
     */
    private $adherent;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"consultation:read"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Diagnostic::class, inversedBy="consultations")
     * @Groups({"consultation:read"})
     */
    private $diagnostic;

    /**
     * @ORM\OneToMany(targetEntity=Prescription::class, mappedBy="consultation")
     * @Groups({"consultation:read"})
     */
    private $prescription;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"consultation:read"})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime" , nullable=true)
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="consultations")
     * @Groups({"consultation:read"})
     */
    private $physician;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"consultation:read"})
     */
    private $filePrescription;

    /**
     * @ORM\ManyToOne(targetEntity=ToothCare::class, inversedBy="consultations")
     * @Groups({"consultation:read"})
     */
    private $toothcare;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"consultation:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"consultation:read"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"consultation:read"})
     */
    private $age;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"consultation:read"})
     */
    private $detail;

    /**
     * @ORM\Column(type="string", length=255 ,nullable=true )
     * @Groups({"consultation:read"})
     */
    private $gender;


    public function __construct()
    {
        $this->prescription = new ArrayCollection();
    }

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDiagnostic(): ?Diagnostic
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(?Diagnostic $diagnostic): self
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    /**
     * @return Collection|Prescription[]
     */
    public function getPrescription(): Collection
    {
        return $this->prescription;
    }

    public function addPrescription(Prescription $prescription): self
    {
        if (!$this->prescription->contains($prescription)) {
            $this->prescription[] = $prescription;
            $prescription->setConsultation($this);
        }

        return $this;
    }

    public function removePrescription(Prescription $prescription): self
    {
        if ($this->prescription->removeElement($prescription)) {
            // set the owning side to null (unless already changed)
            if ($prescription->getConsultation() === $this) {
                $prescription->setConsultation(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getPhysician(): ?User
    {
        return $this->physician;
    }

    public function setPhysician(?User $physician): self
    {
        $this->physician = $physician;

        return $this;
    }

    public function getFilePrescription(): ?string
    {
        return $this->filePrescription;
    }

    public function setFilePrescription(?string $filePrescription): self
    {
        $this->filePrescription = $filePrescription;

        return $this;
    }

    public function getToothcare(): ?ToothCare
    {
        return $this->toothcare;
    }

    public function setToothcare(?ToothCare $toothcare): self
    {
        $this->toothcare = $toothcare;

        return $this;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): self
    {
        $this->detail = $detail;

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
}
