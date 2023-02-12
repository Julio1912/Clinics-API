<?php

namespace App\Controller\admin;

use DateTime;
use App\Entity\Drug;
use App\Form\DrugType;
use App\Form\AppresailType;
use App\Entity\DrugInventory;
use App\Services\CalculeService;
use App\Services\RedirectService;
use App\Services\InventoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/drugstore" , name ="drugstore.")
*/
class DrugStoreController extends AbstractController
{
    protected $session;
    protected $em;
    protected $calculeService;
    protected $inventoryService;
    public function __construct(EntityManagerInterface $em, InventoryService $inventoryService , 
    RedirectService $redirectService , CalculeService $calculeService)
    {
        $this->em               = $em;
        $this->calculeService   = $calculeService;
        $this->session          = new Session();
        $this->redirectService  = $redirectService;
        $this->inventoryService = $inventoryService;
    }
    /**
     * @Route("/", name="list")
     */
    public function drugList(): Response
    {
        
        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {

            # code...
            $listDrugS = $this->em->getRepository(Drug::class)->findBy([] , ['name' => 'ASC']);
            return $this->render('admin/drug_store/list.html.twig', [
                'drugLists' => $listDrugS,
            ]);

        }
      
    }

    /**
     * @Route("/add", name="add")
     * @Route("/edit/{id}", name="edit")
     */
    public function drugForm(Request $request, Drug $drug = null){
       
        if(!$drug){
            $drug = new Drug();
        }else{
            $this->session->set('quantity', $drug->getQuantity());
        }
        // $druginventory = new DrugInventory();
        $form = $this->createForm(DrugType::class, $drug);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            if( $this->session->get('quantity')){
                $this->inventoryService->setInventory($drug, 'ADD_DRUG_QUANTITIES');
                $drug->setQuantity( $this->session->get('quantity' ) + $drug->getQuantity() );
            }else{
                $this->inventoryService->setInventory($drug, 'NEW_DRUG');
            }

            // if($drug->getType() == 'INJ'){
            //     $drug->setMeasure('CC');
            // }else if ($drug->getType() == 'CMP') {
            //     $drug->setMeasure('Comprimé(s)');
            // } else if($drug->getType() == 'AMP'){
            //     $drug->setMeasure('Ampoule(s)');
            // }
            $this->em->persist($drug);
            $this->em->flush();
            $this->session->remove('quantity');
            return $this->redirectToRoute('drugstore.list');
        }
        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;

        }else {
            return $this->render('admin/drug_store/add.html.twig', [
                "formDrug" => $form->createView(),
                "editMode"              => $drug->getId() != null ,
            ]);
        }
    }

    /**
     * @Route("/simple/bring", name="simple.bring")
     */
    public function drugSimpleBring(Request $request): Response
    {
        
        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {

            # code...
            if(count($request->request) > 0){
                for($i = 0; $i < count($request->request->get('drugName')); $i++ ){
                    
                    $drugName       = intval($request->request->get('drugName')[$i]);
                    $quantity       = intval($request->request->get('quantity')[$i]);
                 
                    $drug = $this->em->getRepository(Drug::class)->findOneBy(['id' => $drugName]) ;
                    
                    if ($drug->getQuantity() <= $quantity) {
                        # code...
                        $lastQuantity = 0 ;
                        $this->inventoryService->setBringInventory($drug, 'PRESCRIPTION' , $drug->getQuantity());
                    }else {
                        # code...
                        $lastQuantity = intval($drug->getQuantity()) - $quantity ;
                        $this->inventoryService->setBringInventory($drug, 'PRESCRIPTION' , $quantity);

                    }

                    
                    $drug->setQuantity($lastQuantity);
                  

                    $this->em->persist($drug) ;
                   
                }
                
                $this->em->flush() ;
                
                return $this->redirectToRoute('drugstore.list') ;
            }


            $listDrugS = $this->em->getRepository(Drug::class)->findDrug();

            return $this->render('admin/drug_store/simpleBring.html.twig', [
                'drugLists' => $listDrugS,
            ]);

        }
      
    }

    /**
     * @Route("/inventory", name="inventory")
     */
    public function drugInventory(Request $request): Response
    {
        $today = new DateTime() ;
        $monthToday = $today->format('m') ;
        $yearsToday = $today->format('Y') ;

        $form = $this->createForm(AppresailType::class) ;
        
        $form->handleRequest($request) ; 

        if($form->isSubmitted() && $form->isValid()){

            $monthToday = $form->get('month')->getData() ;
            $yearsToday = $form->get('years')->getData() ;
        
        }

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {

            # code...
            $listDrugS = $this->em->getRepository(Drug::class)->findBy([] , ['name' => 'ASC']);
            return $this->render('admin/drug_store/inventory.html.twig', [
                'drugLists'     => $listDrugS,
                'monthToday'    => $monthToday,
                'yearsToday'    => $yearsToday,
                "formSearch"    => $form->createView()
            ]);

        }
      
    }

    public function countEnterDrugInventory($drug , $month , $years)
    {
        $datas          = [] ;
        $boxEnter       = 0 ; 
        $tabletEnter    = 0 ;
        $drugInventorys = $this->em->getRepository(DrugInventory::class)->findPerDrug(
            $drug , $years  , $month) ;

        if ($drugInventorys) {
            # code...
            foreach ($drugInventorys as $drugInventory) {
                # code...
                $boxEnter    += intval($drugInventory->getQuantity()) ;
                // $tabletEnter += intval($drugInventory->getTabletquantity()) ;

            }
        }
        $datas=[
            'boxEnter'      => $boxEnter ,
            // 'boxEnter'      => $boxEnter ,
            // 'tabletEnter'   => $tabletEnter ,
        ] ;

        return new JsonResponse($datas) ; 

    }

    public function countOutDrugInventory($drug , $month , $years)
    {
        $datas          = [] ;
        $boxOut       = 0 ; 
        $tabletOut    = 0 ;
        $drugInventorys = $this->em->getRepository(DrugInventory::class)->findOutPerDrug(
            $drug , $years  , $month) ;

        if ($drugInventorys) {
            # code...
            foreach ($drugInventorys as $drugInventory) {
                # code...
                $boxOut    += intval($drugInventory->getQuantity()) ;
                // $boxOut    += intval($drugInventory->getBoxQuantity()) ;
                // $tabletOut += intval($drugInventory->getTabletquantity()) ;

            }
        }
        $datas=[
            'boxOut'      => $boxOut ,
            // 'tabletOut'   => $tabletOut ,
        ] ;

        return new JsonResponse($datas) ; 

    }

    public function calculeTotalDrug($params = [])
    {

        // return $this->calculeService->calculeTotalDrug($params) ;

    }

    public function listRoles()
    {
        $listRoles = ["ROLE_DRUG_STORE" , "ROLE_ADMIN"] ;

        return $listRoles ;

    }



}
