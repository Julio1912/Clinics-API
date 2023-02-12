<?php

namespace App\Controller\admin;

use App\Entity\Prescription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/prescription", name="prescription.")
 */

class PrescriptionController extends AbstractController
{
    protected $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    /**
     * @Route("/", name="list")
     */
    public function index(): Response
    {
        $listPrescriptions = $this->em->getRepository(Prescription::class)->findAll();
        return $this->render('admin/prescription/listPrescription.html.twig', [
            'controller_name' => 'PrescriptionController',
            'listPrescriptions' => $listPrescriptions
        ]);
    }
}
