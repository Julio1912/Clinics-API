<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DrugRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=DrugRepository::class)
 * @UniqueEntity(
 * fields={"name"},
 * message="Ce médicament existe déjà dans le stock, veuiller et mettre à jour juste le nombre de nouveaux médicaments"
 * )
 */
class Drug
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
     * @ORM\OneToMany(targetEntity=DrugInventory::class, mappedBy="drug")
     */
    private $drugInventories;

    /**
     * @ORM\OneToMany(targetEntity=Prescription::class, mappedBy="drugName")
     */
    private $prescriptions;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $measure;

    public function __construct()
    {
        $this->drugInventories = new ArrayCollection();
        $this->prescriptions = new ArrayCollection();
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
            $drugInventory->setDrug($this);
        }

        return $this;
    }

    public function removeDrugInventory(DrugInventory $drugInventory): self
    {
        if ($this->drugInventories->removeElement($drugInventory)) {
            // set the owning side to null (unless already changed)
            if ($drugInventory->getDrug() === $this) {
                $drugInventory->setDrug(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Prescription[]
     */
    public function getPrescriptions(): Collection
    {
        return $this->prescriptions;
    }

    public function addPrescription(Prescription $prescription): self
    {
        if (!$this->prescriptions->contains($prescription)) {
            $this->prescriptions[] = $prescription;
            $prescription->setDrugName($this);
        }

        return $this;
    }

    public function removePrescription(Prescription $prescription): self
    {
        if ($this->prescriptions->removeElement($prescription)) {
            // set the owning side to null (unless already changed)
            if ($prescription->getDrugName() === $this) {
                $prescription->setDrugName(null);
            }
        }

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMeasure(): ?string
    {
        return $this->measure;
    }

    public function setMeasure(?string $measure): self
    {
        $this->measure = $measure;

        return $this;
    }
}
