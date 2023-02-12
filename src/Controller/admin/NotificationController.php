<?php

namespace App\Controller\admin;

use App\Entity\Drug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
* @Route("/admin/notification" , name ="notification.")
*/
class NotificationController extends AbstractController
{
    protected $em;
    protected $serializer;

    public function __construct(EntityManagerInterface $em , SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    public function serializeData($data)
    {
        return $this->serializer->serialize($data, 'json');
    }

    /**
    * @Route("/drug" , name ="drug")
    */
    public function drugCount()
    {

        $drugLists = $this->em->getRepository(Drug::class)->findByDrugStore() ;

        $drugCount = (count($drugLists) != 0) ? count($drugLists) : 0 ;

        return new JsonResponse($drugCount) ;
    
    }
    /**
    * @Route("/drug/list" , name ="drug.list")
    */
    public function drugLists()
    {
        $datas = [] ;

        $drugLists = $this->em->getRepository(Drug::class)->findByDrugStore() ;
        
        // $drugCount = (count($drugLists) != 0) ? count($drugLists) : 0 ;
        foreach ($drugLists as $drugList) {
            # code...
            $datas[] = [

                'name'          => $drugList->getName() ,
                'boxQuantity'  => $drugList->getQuantity() ,
                
            ] ;
        }

        $data = $this->serializeData($datas) ;
        
        return new JsonResponse(
            [
                "data" => json_decode($data , true)   ,
            ]
        ) ;

        // return $this->render('admin/notification/drugLists.html.twig',[ 
        //     "drugLists"    => $drugLists ,
        // ]);
    
    }



}