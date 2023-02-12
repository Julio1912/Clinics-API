<?php 
namespace App\Services;

use App\Entity\Adherent;
use App\Entity\Worker;
use Doctrine\ORM\EntityManagerInterface;

Class AdherentService{
    protected $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function callForm(){
        
    }

    public function changeFamiliesStatus(Adherent $adherent){
        $families   = $this->em->getRepository(Adherent::class)->findBy(['worker' => $adherent ]);
        foreach($families as $family){
            if ($adherent->getStatus() === $family->getStatus() ) {
                # code...
                $family->setStatus(!$family->getStatus());
                $this->em->persist($family);
            }
            
        }
    }
}