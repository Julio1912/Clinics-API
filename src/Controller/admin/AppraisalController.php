<?php

namespace App\Controller\admin;

use DateTime;
use App\Entity\ToothCare;
use App\Entity\Diagnostic;
use App\Form\AppresailType;
use App\Entity\Consultation;
use App\Entity\Establishment;
use App\Repository\DiagnosticRepository;
use App\Services\DateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("admin/appraisal", name="appraisal.")
*/
class AppraisalController extends AbstractController
{
    protected $em;
    protected $dateService;

    public function __construct(EntityManagerInterface $em , DateService $dateService)
    {
        $this->em           = $em;
        $this->dateService  = $dateService;

    }

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(AppresailType::class) ;

        $today = new DateTime() ;
        $monthToday = $today->format('m') ;
        $yearsToday = $today->format('Y') ;
 
        $diagnosticLists    = $this->em->getRepository(Diagnostic::class)->findAll();
        // $diagnosticLists    = $this->em->getRepository(Diagnostic::class)->findBy(["id" => 1]);

        $toothCareLists     = $this->em->getRepository(ToothCare::class)->findBy(['status' => true]) ;

        $form->handleRequest($request) ; 

        if($form->isSubmitted() && $form->isValid()){

            $monthToday = $form->get('month')->getData() ;
            $yearsToday = $form->get('years')->getData() ;

            
        
        }

        $_SESSION['monthToday'] = $monthToday ; 
        $_SESSION['yearsToday'] = $yearsToday ;
        
        return $this->render('admin/appraisal/detail.html.twig', [

            'today'             => $today,
            'monthToday'        => $monthToday,
            'yearsToday'        => $yearsToday,
            'diagnosticLists'   => $diagnosticLists,
            'toothCareLists'    => $toothCareLists,
            'formSearch'        => $form->createView(),
            'controller_name'   => 'AppraisalController',
            "link_consultation" =>$this->generateUrl('appraisal.export.consultation', [$request , $yearsToday , $monthToday])
            // 'link_consultation' => $this->exportConsultation($request , $yearsToday , $monthToday)

        ]);


    }

     /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detail(Establishment $establishment): Response
    {

        $societyLists = $this->em->getRepository(Establishment::class)->findBy(['status' => true]) ;
        // $societyLists = $this->em->getRepository(Establishment::class)->findAll() ;
        $today = new DateTime() ;

        return $this->render('admin/appraisal/index.html.twig', [
            'establishement'    => $establishment ,
            'today'             => $today,
            'societyLists'      => $societyLists,
            'controller_name'   => 'AppraisalController',

        ]);


    }

/******************************Complementary function****************************************/
    
    public function countAppraisalMonth($society)
    {

        $count = 0 ;

        $today = new DateTime() ;
        $month  = $today->format('m') ;

        $consultations = $this->em->getRepository(Consultation::class)->findConsultation($society , $month) ;

        if ($consultations) {
            # code...
            $count = count($consultations) ;
        }

        return new response($count) ; 

    }

    
    public function countAllAppraisal($society)
    {

        $count = 0 ;

        $consultations = $this->em->getRepository(Consultation::class)->findAllConsultation($society) ;

        if ($consultations) {
            # code...
            $count = count($consultations) ;
        }

        return new response($count) ; 

    }
    
    public function countConsultationPerDiagnostic($diagnostic  , $years , $month)
    {

        $count = 0 ;

        $consultations = $this->em->getRepository(Consultation::class)->findConsultationPerDiagnostic(
            $diagnostic , $years  , $month) ;
        if ($consultations) {
            # code...
            $count = count($consultations) ;
        }

        return new response($count) ; 

    }

    public function countConsultationPerAgeMale($diagnostic , $years , $month)
    {
        # code...
        $datas          = [] ;
        $totals         = 0 ;
        $firstYears     = 0 ;
        $secondYears    = 0 ;
        $thirdYears     = 0 ;
        $fourthYears    = 0 ;
        $fiftyYears     = 0 ;
        $firstMonths    = 0 ;
        $firstDays      = 0 ;
        $secondDays     = 0 ;

        $consultationLists = $this->em->getRepository(Consultation::class)->findConsultationPerAgeMale(
            $diagnostic , $years , $month
        ) ;
      
        foreach ($consultationLists as $consultationList) {
            # code...
            //ok
            if ($consultationList->getAdherent() != null or !empty($consultationList->getAdherent() ) ) {
                # code...
                $birthday = $consultationList->getAdherent()->getBirthday() ;

                $dateDiff = $this->dateService->traitementDifferenceDate($birthday) ;

                $dateDiffJson = json_decode($dateDiff->getContent() , true) ;
                
                $years  = $dateDiffJson["years"] ;
                $months = $dateDiffJson["months"] ;
                $days   = $dateDiffJson["days"] ;
                
            }
            if($consultationList->getAdherent() == null or empty($consultationList->getAdherent())) {
                # code...
                
                $years = $consultationList->getAge() ;
               
            }
     
            if($years >=1){
                
                if ($years>=1 && $years<5) {
                    # code...
                    $firstYears+= 1 ;


                }elseif ($years>=5 && $years<15) {
                    # code...
                    $secondYears+= 1;

                }elseif ($years>=15 && $years<25) {
                    # code...
                    $thirdYears+= 1 ;

                }elseif ($years>=25 && $years<60) {
                    # code...
                    $fourthYears+= 1 ;

                }
                elseif($years>=60) {
                    # code...
                    $fiftyYears+= 1;
                }
            }
            
            else {
               
                # code...
                if($months >= 2){

                    // if ($months >=2 && $months <=11) {
                        # code...
                        $firstMonths+= 1 ;
    
                    // }

                }
                // elseif($months < 2) {
                //     # code...
                //     $secondDays+= 1 ;
                    
                    
                // }
                else {
                    # code...
                    if ($months > 0) {
                        # code...
                        $secondDays+= 1 ;
                    }
                    else {
                        # code...
                        // if ($days>= 0 && $days<29) {
                            # code...
                            $firstDays+= 1 ;
        
                        // }
                    }
                }
            } 

        }
        
        $datas = [
            'firstYears'    => $firstYears ,
            'secondYears'   => $secondYears ,
            'thirdYears'    => $thirdYears ,
            'fourthYears'   => $fourthYears ,
            'fiftyYears'    => $fiftyYears ,
            'firstMonths'   => $firstMonths ,
            'firstDays'     => $firstDays , 
            'secondDays'    => $secondDays ,
        ] ;
        
        return new JsonResponse([
            $datas
        ]) ;

    }

    

    public function countConsultationPerAgeFemale($diagnostic , $years , $month)
    {
        # code...
        $datas          = [] ;
        $totals         = 0 ;
        $firstYears     = 0 ;
        $secondYears    = 0 ;
        $thirdYears     = 0 ;
        $fourthYears    = 0 ;
        $fiftyYears     = 0 ;
        $firstMonths    = 0 ;
        $firstDays      = 0 ;
        $secondDays     = 0 ;

        $consultationLists = $this->em->getRepository(Consultation::class)->findConsultationPerAgeFemale(
            $diagnostic , $years , $month
        ) ;
        // $consultationLists = $this->em->getRepository(Consultation::class)->findBy(['gender' => 'femme']) ;
        foreach ($consultationLists as $consultationList) {
            
            # code...
            if ($consultationList->getAdherent() != null or !empty($consultationList->getAdherent() ) ) {
                # code...
                $birthday = $consultationList->getAdherent()->getBirthday() ;

                $dateDiff = $this->dateService->traitementDifferenceDate($birthday) ;

                $dateDiffJson = json_decode($dateDiff->getContent() , true) ;
                
                $years  = $dateDiffJson["years"] ;
                $months = $dateDiffJson["months"] ;
                $days   = $dateDiffJson["days"] ;
                
            }
            if($consultationList->getAdherent() == null or empty($consultationList->getAdherent())) {
                # code...
                
                $years = $consultationList->getAge() ;
               
            }

            if($years >=1){
                
                if ($years>=1 && $years<5) {
                    # code...
                    $firstYears+= 1 ;


                }elseif ($years>=5 && $years<15) {
                    # code...
                    $secondYears+= 1;

                }elseif ($years>=15 && $years<25) {
                    # code...
                    $thirdYears+= 1 ;

                }elseif ($years>=25 && $years<60) {
                    # code...
                    $fourthYears+= 1 ;

                }
                elseif($years>=60) {
                    # code...
                    $fiftyYears+= 1;
                }
            }
            else{
                # code...
                if($months >= 2){

                    // if ($months >=2 && $months <=11) {
                        # code...
                        $firstMonths+= 1 ;
    
                    // }

                }
                // elseif($months < 2) {
                //     # code...
                //     $secondDays+= 1 ;
                    
                    
                // }
                else {
                    # code...
                    if ($months > 0) {
                        # code...
                        $secondDays+= 1 ;
                    }
                    else {
                        # code...
                        // if ($days>= 0 && $days<29) {
                            # code...
                            $firstDays+= 1 ;
        
                        // }
    
                    }
                }
            } 
            
        }
        $datas = [
            'firstYears'    => $firstYears ,
            'secondYears'   => $secondYears ,
            'thirdYears'    => $thirdYears ,
            'fourthYears'   => $fourthYears ,
            'fiftyYears'    => $fiftyYears ,
            'firstMonths'   => $firstMonths ,
            'firstDays'     => $firstDays , 
            'secondDays'    => $secondDays ,
        ] ;

        return new JsonResponse([
            $datas
        ]) ;

    }

    public function countConsultationPerDiagnosticYears($diagnostic  , $years )
    {

        $count = 0 ;


        $consultations = $this->em->getRepository(Consultation::class)->countConsultationPerDiagnosticYears(
            $diagnostic , $years ) ;
        
        if ($consultations) {
            # code...
            $count = count($consultations) ;
        }

        return new response($count) ; 

    }

    public function countConsultationPerToothCare($toothcare  , $years , $month)
    {

        $count = 0 ;

        $consultations = $this->em->getRepository(Consultation::class)->findConsultationPerToothCare(
            $toothcare , $years  , $month) ;

        if ($consultations) {
            # code...
            $count = count($consultations) ;
        }

        return new response($count) ; 

    }

    public function countConsultationToothCarePerAge($toothcare , $years , $month)
    {
        # code...
        $datas          = [] ;
        $firstYears     = 0 ;
        $secondYears    = 0 ;

        $consultationLists = $this->em->getRepository(Consultation::class)->findConsultationToothCarePerAge(
            $toothcare , $years , $month
        ) ;

        foreach ($consultationLists as $consultationList) {
            # code...
            $birthday = $consultationList->getAdherent()->getBirthday() ;

            $dateDiff = $this->dateService->traitementDifferenceDate($birthday) ;

            $dateDiffJson = json_decode($dateDiff->getContent() , true) ;
            
            $years  = $dateDiffJson["years"] ;
            $months = $dateDiffJson["months"] ;
            $days   = $dateDiffJson["days"] ;
            
            if($years < 15){
            
                $firstYears += 1 ;
            
            }else {
                # code...
                $secondYears += 1 ;
            
            }
        }
        $datas = [
            'firstYears'    => $firstYears ,
            'secondYears'   => $secondYears ,
        ] ;

        return new JsonResponse([
            $datas
        ]) ;

    }

    public function countConsultationPerToothCareYears($toothcare  , $years )
    {

        $count = 0 ;


        $consultations = $this->em->getRepository(Consultation::class)->countConsultationPerToothCareYears(
            $toothcare , $years ) ;

        if ($consultations) {
            # code...
            $count = count($consultations) ;
        }

        return new response($count) ; 

    }

    /**
     * @Route("/time", name="time")
     */
    public function groupMonthPerConsultation($society)
    {
        $datas = [] ;
        $month = ["Janvier","Fevrier","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre",
        "Octobre","Novembre","Décembre"] ;

        $timeOfConsultations = $this->em->getRepository(Consultation::class)->findTime($society) ;
        
        foreach ($timeOfConsultations as $timeOfConsultation) {
            # code...
            $datas[] =  $month[intval($timeOfConsultation['month']-1)]." ".$timeOfConsultation['year'];
        }

        return new JsonResponse([

            'data' => $datas ,

            ]) ;

    }

    public function returnDate($today)
    {
        $datas = [] ;
        $month = ["" ,"Janvier","Fevrier","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre",
        "Octobre","Novembre","Décembre"] ;
        
        return new Response($month[intval($today)]) ;

    }

    
    /**
     * @Route("/export/consultation", name="export.consultation")
     */
    public function exportConsultation(Request $request ){

        $years  = $_SESSION['yearsToday'] ; 
        $months = $_SESSION['monthToday'] ; 
       
        $baseurl = $request->getScheme() .'://'. $request->getHttpHost().$request->getBasePath();
        $response = new StreamedResponse();
        $response->setCallback(function() use(&$baseurl , $years , $months) {
            $handle = fopen('php://output', 'w+');

            // Ajout UTF8
            fputs($handle, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

            $delimiter = ";";
            
            // Add the header of the CSV file
            fputcsv($handle, ['Maladie', '0-28 j','', '29-59 j','','2-11 m','','1-4 ans','','5-14 ans','','15-24 ans','','25-59 ans','','+60 ans'], $delimiter);
            fputcsv($handle, ['', 'H','F', 'H','F', 'H','F', 'H','F', 'H','F', 'H','F', 'H','F', 'H','F'], $delimiter);

            // Query data from database
            // $bddrepository = $this->getDoctrine()->getRepository(Bdd::class);
            // $allbdd = $bddrepository->findBy(array(), array('created' => 'desc'));
            
            // $today = new \DateTime() ;
            
            // $month = $today->format('m') ;
            // $years = $today->format('y') ;


            $listDiagnostics = $this->em->getRepository(Diagnostic::class)->findAll() ;
           

            foreach ($listDiagnostics as $diagnostic) {

                $dataFemales = $this->countConsultationPerAgeFemale($diagnostic , $years  , $months) ; 

                $dataMales   = $this->countConsultationPerAgeMale($diagnostic , $years , $months) ;

                $dataFemale = json_decode($dataFemales->getContent() , true) ;
                $dataMale   = json_decode($dataMales->getContent() , true) ;
                
                fputcsv(
                    $handle, // The file pointer 
                    [
                        $diagnostic->getName(), 
                        $dataMale[0]['firstDays'],
                        $dataFemale[0]['firstDays'],
                        $dataMale[0]['secondDays'],
                        $dataFemale[0]['secondDays'],
                        $dataMale[0]['firstMonths'],
                        $dataFemale[0]['firstMonths'],
                        $dataMale[0]['firstYears'],
                        $dataFemale[0]['firstYears'],
                        $dataMale[0]['secondYears'],
                        $dataFemale[0]['secondYears'],
                        $dataMale[0]['thirdYears'],
                        $dataFemale[0]['thirdYears'],
                        $dataMale[0]['fourthYears'],
                        $dataFemale[0]['fourthYears'],
                        $dataMale[0]['fiftyYears'],
                        $dataFemale[0]['fiftyYears']
                        
                    ], // The fields
                    $delimiter // The delimiter
                );
            }

            fclose($handle);
        });

        $datenow = (new \DateTime())->format('dmYHis');
        $filename = "export_consultation_".$datenow.".csv";
        $response->headers->set('Content-Type', 'application/force-download; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);

        return $response;
    }

    /**
     * @Route("/export/toothcare", name="export.toothcare")
     */
    public function exportToothcare(Request $request ){

        $years  = $_SESSION['yearsToday'] ; 
        $months = $_SESSION['monthToday'] ; 

        $baseurl = $request->getScheme() .'://'. $request->getHttpHost().$request->getBasePath();
        $response = new StreamedResponse();
        $response->setCallback(function() use(&$baseur , $years , $months ) {
            $handle = fopen('php://output', 'w+');

            // Ajout UTF8
            fputs($handle, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

            $delimiter = ";";

            // Add the header of the CSV file
            fputcsv($handle, ['Maladie', '>15 ans', 'Plus de 15 ans'], $delimiter);

            // Query data from database
            // $bddrepository = $this->getDoctrine()->getRepository(Bdd::class);
            // $allbdd = $bddrepository->findBy(array(), array('created' => 'desc'));

            $toothCareLists = $this->em->getRepository(ToothCare::class)->findAll() ;


            foreach ($toothCareLists as $toothcare) {

                $toothcares = $this->countConsultationToothCarePerAge($toothcare , $years , $months) ;
                
                $dataToothcare = json_decode($toothcares->getContent() , true) ;

                    fputcsv(
                        $handle, // The file pointer 
                        [
                            $toothcare->getName(), 
                            $dataToothcare[0]['firstYears'] , 
                            $dataToothcare[0]['secondYears']
                            
                            
                        ], // The fields
                        $delimiter // The delimiter
                    );
            }

            fclose($handle);
        });

        $datenow = (new \DateTime())->format('dmYHis');
        $filename = "export_activite_dentiste".$datenow.".csv";
        $response->headers->set('Content-Type', 'application/force-download; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);

        return $response;
    }


}
