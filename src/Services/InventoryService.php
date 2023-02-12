<?php 
namespace App\Services;

use App\Entity\Drug;
use App\Entity\DrugInventory;
use App\Entity\Prescription;
use Doctrine\DBAL\Types\StringType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class InventoryService extends AbstractController{
    protected $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setInventory(Drug $drug, String $type, Prescription $prescription = null){
        $druginventory = new DrugInventory();

        if ($prescription) {
            $druginventory->setDrug($drug);
            $druginventory->setType($type);
            $druginventory->setQuantity($prescription->getTotalDrugs());
            $druginventory->setCreated(new \DateTime());
            $druginventory->setPrescription($prescription);
        }else {
            $druginventory->setDrug($drug);
            $druginventory->setType($type);
            $druginventory->setQuantity($drug->getQuantity());
            $druginventory->setCreated(new \DateTime());
        }

        $this->em->persist($druginventory);
    }

    public function setBringInventory(Drug $drug, String $type , $quantity ){
        $druginventory = new DrugInventory();

        $druginventory->setDrug($drug);
        $druginventory->setType($type);
        $druginventory->setQuantity($quantity);
        $druginventory->setCreated(new \DateTime());
        
        $this->em->persist($druginventory);
    }

}