<?php 
namespace App\Services;

use App\Entity\Drug;
use App\Entity\Prescription;
use Doctrine\ORM\EntityManagerInterface;

class CalculeService
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function convertTabletsToBox(Drug $drug){
        $tabletsQuantities  = $drug->getTabletquantity();
        $boxQuantities      = $drug->getBoxquantity();
        $tabletPerBox       = $drug->getTabletperbox();

        if( $tabletsQuantities >= $tabletPerBox )
        {
            $boxes      = floor($tabletsQuantities / $tabletPerBox);
            $tablets    = $drug->getTabletquantity() - ($boxes * $drug->getTabletperbox());
            $drug->setBoxquantity( $boxes + $boxQuantities );
            $drug->setTabletquantity($tablets);
        }
        return $drug;
    }

    public function calculTotalAmountPrescription(Prescription $prescription)
    {

        $boxunitprice       = $prescription->getDrugName()->getBoxunitprice();
        $tabletunitprice    = $prescription->getDrugName()->getTabletunitprice();
        $tabletAmount       = $tabletunitprice * $prescription->getTotalTablet();
        $boxAmount          = $boxunitprice * $prescription->getTotalBox();

        return $tabletAmount + $boxAmount;

    }

    public function getOutPrescriptionDrugTotal(Prescription $prescription)
    {
        $drug = $prescription->getDrugName();

        if($tabletsQuantities < $totalTablet && $boxQuantities > 0){

            $drug->setBoxquantity(($boxQuantities - 1) - $totalBox);
            $drug->setTabletquantity(($tabletsQuantities + $drug->getTabletperbox()) - $totalTablet);

        }else{

            $drug->setBoxquantity($boxQuantities - $totalBox);
            $drug->setTabletquantity($tabletsQuantities - $totalTablet);

        }
        
        $this->em->persist($drug) ;

    }
}