<?php 
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class TraitementData
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function listDiagnostic()
    {

        $datas = [
            [
                'name'          => 'Ancien cas' , 
                'description'   => ''
            ] , 
            [
                'name'          => "Signes d'infection bactériennes graves" , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Pneumonie sévère' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Dysenterie/Diarhée sanguinolente' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Suspicion de fièvre typhoide' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Diarhées avec déshydratation' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Diarhées sans déshydratation' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Suspicion de choléra' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Syndrome grippal' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Varicelle' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Oreillon' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'IRA(Toux + Rhume ou Maux de gorge ou Otite et Fièvre depuis moins de 15j)' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Pneumonie' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Toux ou rhume' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Asthme' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Maux de gorge' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'MAPI grave' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'MAPI mineur' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Suspicion de rougeole/rubéole' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Diphtérie' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Coqueluche' , 
                'description'   => ''
            ] , 
            [
                'name'          => 'Tétanos' , 
                'description'   => ''
            ]
        ] ;

        return $datas ;

    }

    public function listToothCare()
    {
        $datas = [
            [
                'name' => 'Extraction soins'
            ] , 
            [
                'name' => 'Soins obturateur'
            ] , 
            [
                'name' => 'Autres soin'
            ] , 
            [
                'name' => 'Radiographie'
            ] , 
        ] ;
        
        
        return $datas ;

     }

}