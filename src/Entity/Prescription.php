<?php

namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrescriptionRepository::class)
 */
class Prescription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=DrugInventory::class, mappedBy="prescription")
     */
    private $drugInventories;

    

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfDayPrescribed;

    /**
     * @ORM\Column(type="integer")
     */
    private $morning;

    /**
     * @ORM\Column(type="integer")
     */
    private $noon;

    /**
     * @ORM\Column(type="integer")
     */
    private $evening;

    /**
     * @ORM\ManyToOne(targetEntity=Consultation::class, inversedBy="prescription")
     */
    private $consultation;

    /**
     * @ORM\ManyToOne(targetEntity=Drug::class, inversedBy="prescriptions")
     */
    private $drugName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalDrugs;

    public function __construct()
    {
        $this->drugInventories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|DrugInventory[]
     */
    public function getDrugInventories(): Collection
    {
        return $this->drugInventories;
    }

    public function addDrugInventory(DrugInventory $drugInventory): self
    {
        if (!$this->drugInventories->contains($drugInventory)) {
            $this->drugInventories[] = $drugInventory;
            $drugInventory->setPrescription($this);
        }

        return $this;
    }

    public function removeDrugInventory(DrugInventory $drugInventory): self
    {
        if ($this->drugInventories->removeElement($drugInventory)) {
            // set the owning side to null (unless already changed)
            if ($drugInventory->getPrescription() === $this) {
                $drugInventory->setPrescription(null);
            }
        }

        return $this;
    }

    public function getNumberOfDayPrescribed(): ?int
    {
        return $this->numberOfDayPrescribed;
    }

    public function setNumberOfDayPrescribed(int $numberOfDayPrescribed): self
    {
        $this->numberOfDayPrescribed = $numberOfDayPrescribed;

        return $this;
    }

    public function getMorning(): ?string
    {
        return $this->morning;
    }

    public function setMorning(string $morning): self
    {
        $this->morning = $morning;

        return $this;
    }

    public function getNoon(): ?string
    {
        return $this->noon;
    }

    public function setNoon(string $noon): self
    {
        $this->noon = $noon;

        return $this;
    }

    public function getEvening(): ?string
    {
        return $this->evening;
    }

    public function setEvening(string $evening): self
    {
        $this->evening = $evening;

        return $this;
    }

    public function getConsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function setConsultation(?Consultation $consultation): self
    {
        $this->consultation = $consultation;

        return $this;
    }

    public function getDrugName(): ?Drug
    {
        return $this->drugName;
    }

    public function setDrugName(?Drug $drugName): self
    {
        $this->drugName = $drugName;

        return $this;
    }

    public function getTotalDrugs(): ?int
    {
        return $this->totalDrugs;
    }

    public function setTotalDrugs(?int $totalDrugs): self
    {
        $this->totalDrugs = $totalDrugs;

        return $this;
    }
}
