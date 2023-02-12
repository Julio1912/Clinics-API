<?php

namespace App\Controller\admin;

use DateTime;
use App\Entity\Drug;
use App\Entity\Adherent;
use App\Entity\ToothCare;
use App\Entity\Diagnostic;
use App\Entity\Consultation;
use App\Entity\Prescription;
use App\Services\DateService;
use App\Form\ConsultationType;
use App\Services\CalculeService;
use App\Services\DomPdfServices;
use App\Services\InventoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;

/**
 * @Route("/admin/consultation" , name ="consultation.")
*/

class ConsultationController extends AbstractController{
    protected $em;
    protected $domPdfServices;
    protected $inventoryService;
    protected $calculeService;
    protected $dateService;
    protected $serializer ; 

    public function __construct(EntityManagerInterface $em, InventoryService $inventoryService 
    , CalculeService $calculeService , DomPdfServices $domPdfServices , DateService $dateService , SerializerInterface $serializer)
    {
        $this->em               = $em;
        $this->domPdfServices   = $domPdfServices;
        $this->inventoryService = $inventoryService;
        $this->calculeService   = $calculeService;
        $this->dateService      = $dateService;
        $this->serializer       = $serializer;
        
    }

    public function deserialize(array $data, string $type, string $format = 'json', array $options = [])
    {
        return $this->serializer->deserialize(
            json_encode($data),
            $type,
            $format,
            $options
        );
    }

    /**
     * @param mixed $data
     * @param string $type
     * @return string
     */
    public function serialize($data, string $type, $context = [])
    {
        return $this->serializer->serialize($data, $type , $context);
    }

    /**
     * @Route("/", name="list")
     */
    public function consultationList(): Response
    {
        if(in_array("ROLE_MEDC" , $this->getUser()->getRoles())){

            $listConsultations   = $this->em->getRepository(Consultation::class)->findBy([] , ['id' => 'DESC']);
            $listConsultationsNonLivres = $this->em->getRepository(Consultation::class)->findBy(['physician' => $this->getUser(),'status'=> 0],['id'=> 'DESC']);
            $listConsultationsLivres = $this->em->getRepository(Consultation::class)->findBy(['status'=> 1],['id'=> 'DESC']);

        }else {

            # code...
            $listConsultations   = $this->em->getRepository(Consultation::class)->findBy([] , ['id' => 'DESC']);
            $listConsultationsNonLivres = $this->em->getRepository(Consultation::class)->findBy(['status'=> 0],['id'=> 'DESC']);
            $listConsultationsLivres = $this->em->getRepository(Consultation::class)->findBy(['status'=> 1],['id'=> 'DESC']);
        
        }
        // dd($listConsultations[0]->getPrescription()[1]->getId());
        
        return $this->render('admin/consultation/list.html.twig', [
            'consultations'          => $listConsultations,
            'consultationsLivres'    => $listConsultationsLivres,
            'consultationsNonLivres' => $listConsultationsNonLivres
        ]);
    }

    /**
     * @Route("/listConsult/{type}", name="list.consult")
     */
    public function consultationListJson(Request $request)
    {
        
        $type = $request->attributes->get('type');

        switch ($type) {

            case 'all':
                $title = "Liste de tous les consultations" ;

                if(in_array("ROLE_MEDC" , $this->getUser()->getRoles())){
                    $consultations   = $this->em->getRepository(Consultation::class)->findBy([] , ['id' => 'DESC']);
        
                }else {
                    # code...
                    $consultations   = $this->em->getRepository(Consultation::class)->findBy([] , ['id' => 'DESC']);
                
                }
                break;

            case 'undelivered':
                $title = "Liste de tous les consultations non livrees" ;

                if(in_array("ROLE_MEDC" , $this->getUser()->getRoles())){
                    $consultations = $this->em->getRepository(Consultation::class)->findBy(['physician' => $this->getUser(),'status'=> 0],['id'=> 'DESC']);
        
                }else {
                    # code...
                    $consultations = $this->em->getRepository(Consultation::class)->findBy(['status'=> 0],['id'=> 'DESC']);
                
                }
                break;
            
            case 'delivered':
                $title  = " Liste de tous les consultations livrees" ;
                // if(in_array("ROLE_MEDC" , $this->getUser()->getRoles())){
                //    $consultations = $this->em->getRepository(Consultation::class)->findBy(['status'=> 1],['id'=> 'DESC']);
        
                // }else {
                    # code...
                    $consultations = $this->em->getRepository(Consultation::class)->findBy(['status'=> 1],['id'=> 'DESC']);
                
                // }
                break;
        }

       
        // dd($listConsultations[0]->getPrescription()[1]->getId());
        
        return $this->render('admin/consultation/listConsult.html.twig', [
            'consultations' => $consultations,
            'title'         => $title ,
            'type'          => $type ,
            // 'consultationsLivres'    => $listConsultationsLivres,
            // 'consultationsNonLivres' => $listConsultationsNonLivres
        ]);
    }


    /**
     * @Route("/detail/{id}", name="detail.consult")
     */

    public function detailConsultation(Consultation $consultation=null)
    {
        # code...
        if(!$consultation){
            $consultation = new Consultation() ;
        }

        return $this->render('admin/consultation/detail.html.twig', [
            'consultation' => $consultation,
            // 'consultationsLivres'    => $listConsultationsLivres,
            // 'consultationsNonLivres' => $listConsultationsNonLivres
        ]);
    }


    /**json response list consultation */
    public function jsonResponseConsultation()
    {
        # code...
        if(in_array("ROLE_MEDC" , $this->getUser()->getRoles())){

            $listConsultations   = $this->em->getRepository(Consultation::class)->findBy([] , ['id' => 'DESC'] , 500);
            $listConsultationsNonLivres = $this->em->getRepository(Consultation::class)->findBy(['physician' => $this->getUser(),'status'=> 0],['id'=> 'DESC']);
            $listConsultationsLivres = $this->em->getRepository(Consultation::class)->findBy(['status'=> 1],['id'=> 'DESC']);

        }else {

            # code...
            $listConsultations   = $this->em->getRepository(Consultation::class)->findBy([] , ['id' => 'DESC'], 500);
            $listConsultationsNonLivres = $this->em->getRepository(Consultation::class)->findBy(['status'=> 0],['id'=> 'DESC']);
            $listConsultationsLivres = $this->em->getRepository(Consultation::class)->findBy(['status'=> 1],['id'=> 'DESC']);
        
        }

        $consultation                  = $this->serialize($listConsultations, 'json' , ['groups' => 'consultation:read' ]);
        $listConsultationsNonLivres    = $this->serialize($listConsultationsNonLivres, 'json' , ['groups' => 'consultation:read' ]);
        $listConsultationsLivres       = $this->serialize($listConsultationsLivres, 'json' , ['groups' => 'consultation:read' ]);
         
        return new JsonResponse([

            "consultation"                  => json_decode($consultation , true),
            // "listConsultationsNonLivres"    => json_decode($listConsultationsNonLivres , true),
            // "listConsultationsLivres"       => json_decode($listConsultationsLivres , true)

        ]);
    }


    public function traitConsultation($consultations)
    {
        # code...
        $datas = []  ; 

        
        // dd($datas) ;
        
        return $datas ;
    }

    // public function traitDrug($prescriptions)
    // {
    //     # code...
    //     $precs = [] ; 

    //     foreach ($prescriptions as $prescription) {
            
    //         # code...
    //         $precs[] = [
    //             'drugname'          => $prescription->getDrugName()->getName() ,
    //             "morning"           => $prescription->getMorning() , 
    //             "noon"              => $prescription->getNoon() , 
    //             "evening"           => $prescription->getEvening() , 
    //             "measure"           => $prescription->getDrugName()->getMeasure() , 
    //             "numberDayPrescribe"=> $prescription->getNumberOfDayPrescribed() ,
    //             "totalDrugs"        => $prescription->getTotalDrugs() ,
    //         ] ; 
    //     }
        
       
    //     return $precs ;
    // }


    /**
     * @Route("/add/Infos/{id}", name="add")
     * @Route("/add/Infos/", name="add.simple")
     */
    public function addConsultation(Adherent $adherent = null, Request $request)
    {
        $lastConsultation   = 15 ;
        $consultation       = new Consultation();
        $listDrugs          = $this->em->getRepository(Drug::class)->findDrug();
        
        if($adherent){
            $consultation->setAdherent($adherent);
        }

        $checkLastConsultation = $this->em->getRepository(Consultation::class)->findBy(['adherent' =>
        $adherent] , ['id' => 'DESC' ]) ;

        if($checkLastConsultation){

            $lastConsultations  = $checkLastConsultation[0]->getCreated() ;
            $lastConsultation   = $this->dateService->dateDiffefence($lastConsultations) ;
            $allParams          = json_decode(json_encode($lastConsultation) , true) ;
            $lastConsultation   = $allParams['d'] ;
            
        }

        $diagnostics = $this->em->getRepository(Diagnostic::class)->findAll();

        if(count($request->request) > 0){
            $name               = $request->request->get('name') ? $request->request->get('name') : null ;
            $firstname          = $request->request->get('firstname') ? $request->request->get('firstname') : null;
            $age                = $request->request->get('age') ? $request->request->get('age') : null;
            $gender             = $request->request->get('gender') ? $request->request->get('gender') : "";
            $description        = $request->request->get('description') ? $request->request->get('description') : "";
            $typeConsultation   = $request->request->get('typeConsultation');
            $diagnostique       = $request->request->get('diagnostic');
           
            if($typeConsultation == "Activité de dentisterie" ){
                # code...
                $toothcare = $this->em->getRepository(ToothCare::class)->findOneBy(['id' => $diagnostique]) ;
                $consultation->setToothcare($toothcare) ;

            }else {
                # code...
                $diagnostique = $this->em->getRepository(Diagnostic::class)->findOneBy(['id' => $diagnostique]);
                $consultation->setDiagnostic($diagnostique) ;
            }

            $consultation->setType( $typeConsultation )
                         ->setStatus(0)
                         ->setPhysician($this->getUser())
                         ->setCreated(new \DateTime())
                         ->setName($name)
                         ->setFirstname($firstname)
                         ->setAge($age)
                         ->setDetail($description)
                         ->setGender($gender)
            ;
            
            $this->em->persist($consultation);
            // dd($request->request->get('drugName'));
            $consultationAmount = 0;
            for ($i = 0; $i < count($request->request->get('drugName')); $i++ ){
            
                $prescription   = new Prescription();
                $drugName       = $request->request->get('drugName')[$i];
                $day            = $request->request->get('day')[$i];
                $morning        = $request->request->get('morning')[$i];
                $noon           = $request->request->get('noon')[$i];
                $evening        = $request->request->get('evening')[$i];
                $total          = $request->request->get('sum')[$i];
                $drugNameId     = $this->em->getRepository(Drug::class)->findOneBy(['id' => $drugName]);

                /******jerena */
                if (intval($drugNameId->getQuantity()) <= intval($total)) {
                    # code...
                    $total = intval($drugNameId->getQuantity()) ;
                    
                }
                // else {
                //     # code...
                //     $lastQuantity = intval($drugNameId->getQuantity()) - intval($total) ;
                //     $drugNameId->setQuantity($lastQuantity) ;
    
                // }
                // $this->em->persist($drugNameId) ;
                /*****hatreto */

                // dump($drugName) ;
                $prescription->setDrugName($drugNameId)
                             ->setNumberOfDayPrescribed($day)
                             ->setMorning($morning)
                             ->setNoon( $noon)
                             ->setEvening($evening)
                             ->setConsultation($consultation)
                             ->setTotalDrugs($total);

                ;
                // $totalMedecineInADay    = $prescription->getMorning() + $prescription->getNoon() + $prescription->getEvening();
                // $totalMedecine          = ceil($totalMedecineInADay * $prescription->getNumberOfDayPrescribed());

                // $prescription->setTotalDrugs($totalMedecine);
               
                $this->em->persist($prescription);
            }
            
            $this->em->persist($consultation);
            
            $this->em->flush();

            $this->domPdfServices->generateBillPdf($consultation) ;
            
            return $this->redirectToRoute('consultation.list.consult' , ['type'=>"undelivered"]);
        
        }
        return $this->render('admin/consultation/addConsultation.html.twig',[
            // "formConsultation" => $form->createView()
            "diagnostics"           => $diagnostics,
            "adherent"              => $adherent,
            "drugs"                 => $listDrugs ,
            "lastConsultation"      => $lastConsultation,
            "checkLastConsultation" => $checkLastConsultation,
            
        ]);
    }

    /**
     * @Route("/export/{id}", name="export")
     */
    public function exportPdf(Consultation $consultation)
    {

        return $this->domPdfServices->generateBillPdf($consultation) ;

    }

     /**
     * @Route("/diagnostic/list", name="diagnostictype")
     */
    public function diagnosticList(Request $request)
    {

        $datas = [] ;
        $typeConsultation = $request->query->get('typeConsultation');
        if ($typeConsultation == "Activité de dentisterie") {
            # code...
            $diagnostics = $this->em->getRepository(ToothCare::class)->findBy(["status" => true]) ;
        }else {
            # code...
            $diagnostics = $this->em->getRepository(Diagnostic::class)->findAll() ;
        }
        // $subjects = $subjectRepository->findBy(['classschool' => $idclassschool]) ;
        foreach ($diagnostics as $diagnostic) {
            $datas[] = [
                "id"    => $diagnostic->getId() ,
                "name"  => $diagnostic->getName()
            ] ;
        }

        return new JsonResponse($datas, Response::HTTP_OK);

    }

    /**
     * @Route("/validate/{id}", name="validate")
     */
    public function validateConsultation(Consultation $consultation = null){
        if (!$consultation){
            $consultation = new Consultation();
        }
        $prescriptions = $consultation->getPrescription();
        foreach ( $prescriptions as $prescription )
        {
            $drug = $prescription->getDrugName();

            $quantities =  intval($drug->getQuantity());

            
            // $drug->setQuantity( $quantities - $prescription->getTotalDrugs());

            if ($quantities <= $prescription->getTotalDrugs()) {
                # code...
                $lastQuantity = 0 ;
                $this->inventoryService->setBringInventory($drug, 'PRESCRIPTION' , $quantities);

                $prescription->setTotalDrugs($quantities) ;

                $this->em->persist($prescription) ;
                

            }else {
                # code...
                $lastQuantity = $quantities - $prescription->getTotalDrugs() ;
                $this->inventoryService->setBringInventory($drug, 'PRESCRIPTION' , $quantities);

            }

            $drug->setQuantity($lastQuantity) ;

            // $this->inventoryService->setInventory($prescription->getDrugName(), 'PRESCRIPTION', $prescription);

            $this->em->persist($prescription->getDrugName());

        }
        
        $consultation->setStatus(true);
        $this->em->persist($consultation);
        
        $this->em->flush();


        return $this->redirectToRoute('consultation.list.consult' , ['type'=>"undelivered"]);

        return $this->render('admin/consultation/printConsultation.html.twig',[
            // "formConsultation" => $form->createView()
            "consultation" => $consultation,
        ]);

    }

    /**
     * get description diagnostic
     * @Route("/description/diagnostic", name="diagnostic.description" , methods={"GET"})
     */
    public function getDescriptionDiagnostic(Request $request)
    {
        # code...
        $datas          = [] ;
        $idDiagnostic  = $request->query->get('idDiagnostic');
        $diagnostic    = $this->em->getRepository(Diagnostic::class)->findOneBy(['id' => $idDiagnostic]) ;
        if ($diagnostic) {
            # code...
            $datas = [
                "id"            => $diagnostic->getId() ,
                "name"          => $diagnostic->getName() , 
                "description"   => $diagnostic->getDescription()
            ] ;

        }
        return new JsonResponse($datas, Response::HTTP_OK);
    }

    /**count drug per consultation**/
    public function countDrugPerConsultation($drug , $morning , $noon , $evening)
    {
        $total = 0 ;

        return new Response($total) ;

    }

}