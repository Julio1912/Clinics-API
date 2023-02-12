<?php

namespace App\Entity;

use App\Entity\Position;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 */
class Adherent
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $affiliateNumber;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthDay;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Establishment::class, inversedBy="adherents")
     */
    private $establishment;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class, inversedBy="adherents")
     */
    private $position;
    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="adherents")
     */
    private $worker;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255 , nullable = true)
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=WorkIncident::class, mappedBy="adherent")
     */
    private $workIncidents;

    /**
     * @ORM\OneToMany(targetEntity=ProfessionnalSick::class, mappedBy="adherent")
     */
    private $professionnalSicks;

    /**
     * @ORM\OneToMany(targetEntity=ToothCare::class, mappedBy="adherent")
     */
    private $toothCares;

    /**
     * @ORM\OneToMany(targetEntity=Consultation::class, mappedBy="adherent")
     */
    private $consultations;

    public function __construct()
    {
        $this->adherents = new ArrayCollection();
        $this->workIncidents = new ArrayCollection();
        $this->professionnalSicks = new ArrayCollection();
        $this->toothCares = new ArrayCollection();
        $this->consultations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAffiliateNumber(): ?string
    {
        return $this->affiliateNumber;
    }

    public function setAffiliateNumber(?string $affiliateNumber): self
    {
        $this->affiliateNumber = $affiliateNumber;

        return $this;
    }

    public function getBirthDay(): ?\DateTimeInterface
    {
        return $this->birthDay;
    }

    public function setBirthDay(?\DateTimeInterface $birthDay): self
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

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

    public function getWorker(): ?self
    {
        return $this->worker;
    }

    public function setWorker(?self $worker): self
    {
        $this->worker = $worker;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

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
            $workIncident->setAdherent($this);
        }

        return $this;
    }

    public function removeWorkIncident(WorkIncident $workIncident): self
    {
        if ($this->workIncidents->removeElement($workIncident)) {
            // set the owning side to null (unless already changed)
            if ($workIncident->getAdherent() === $this) {
                $workIncident->setAdherent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProfessionnalSick[]
     */
    public function getProfessionnalSicks(): Collection
    {
        return $this->professionnalSicks;
    }

    public function addProfessionnalSick(ProfessionnalSick $professionnalSick): self
    {
        if (!$this->professionnalSicks->contains($professionnalSick)) {
            $this->professionnalSicks[] = $professionnalSick;
            $professionnalSick->setAdherent($this);
        }

        return $this;
    }

    public function removeProfessionnalSick(ProfessionnalSick $professionnalSick): self
    {
        if ($this->professionnalSicks->removeElement($professionnalSick)) {
            // set the owning side to null (unless already changed)
            if ($professionnalSick->getAdherent() === $this) {
                $professionnalSick->setAdherent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ToothCare[]
     */
    public function getToothCares(): Collection
    {
        return $this->toothCares;
    }

    public function addToothCare(ToothCare $toothCare): self
    {
        if (!$this->toothCares->contains($toothCare)) {
            $this->toothCares[] = $toothCare;
            $toothCare->setAdherent($this);
        }

        return $this;
    }

    public function removeToothCare(ToothCare $toothCare): self
    {
        if ($this->toothCares->removeElement($toothCare)) {
            // set the owning side to null (unless already changed)
            if ($toothCare->getAdherent() === $this) {
                $toothCare->setAdherent(null);
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
            $consultation->setAdherent($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getAdherent() === $this) {
                $consultation->setAdherent(null);
            }
        }

        return $this;
    }
}