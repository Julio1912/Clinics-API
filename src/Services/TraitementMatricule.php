<?php 
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class TraitementMatricule extends AbstractController
{
    protected $em ; 
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em ;
    }

    public function affiliateNumberGenerator($total)
    {
        
        if ($total>=0 && $total<9 ) {
            $nextNumber = "00" ;
            $nextNumber.= $total + 1  ;
        }
        elseif ($total>=9 && $total<100) {
            $nextNumber  = "0" ;
            $nextNumber .= $total + 1 ;
        }
        else {
            $nextNumber = $total + 1 ;
        }
        
        return $nextNumber;

    }
    
    public function __toString()
    {

    }
}