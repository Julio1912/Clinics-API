<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
use App\Entity\Announcement;
use App\Entity\AnnouncementObject;
use App\Entity\Bill;
use App\Entity\Prescription;
use App\Repository\BillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DomPdfServices  extends AbstractController
{
    protected $em;
    protected $logoService;
    protected $mangoPayService;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function generateBillPdf($consultation)
    {
        $prescriptions = $this->em->getRepository(Prescription::class)->findBy(['consultation' => $consultation]) ;

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Roboto');
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsHtml5ParserEnabled(true);
        $dompdf         = new Dompdf($pdfOptions);
        $today          = new \DateTime();
        $totalQuantity  = 0;

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('pdf/prescription.html.twig', [
            
            "consultation"  => $consultation ,
            "prescriptions" => $prescriptions ,


        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        // $dompdf->setPaper([0,0,300,490], 'portrait');
        $dompdf->render();

        $output         = $dompdf->output();
        $directory      = $this->getParameter('pdf_consultation_directory');

        $firstname      = is_null($consultation->getAdherent()) ? $consultation->getFirstname() : $consultation->getAdherent()->getFirstname() ;
        $name           =  "Ordonnance-Smisa ".$firstname." ".$consultation->getId(). ".pdf" ;
        $pdfFilepath    =  $directory . "/" . "Ordonnance-Smisa ".$firstname ." ".$consultation->getId(). ".pdf";

        file_put_contents($pdfFilepath, $output);

        $consultation->setFilePrescription($name) ;
        
        $this->em->persist($consultation) ;
        $this->em->flush() ;

        return $pdfFilepath;
    }
}
