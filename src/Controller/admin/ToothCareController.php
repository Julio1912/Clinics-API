<?php

namespace App\Controller\admin;

use App\Entity\Adherent;
use App\Entity\ToothCare;
use App\Form\ToothCareType;
use App\Services\RedirectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/toothcare" , name ="toothcare.")
*/
class ToothCareController extends AbstractController
{
    protected $em;
    protected $redirectService;
    public function __construct(EntityManagerInterface $em , RedirectService $redirectService)
    {
        $this->em               = $em;
        $this->redirectService  = $redirectService;
    }

    /**
     * @Route("/", name="list")
     */
    public function index(): Response
    {
        $listToothCare = $this->em->getRepository(ToothCare::class)->findAll();
        
        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/tooth_care/listToothCare.html.twig', [
                'controller_name' => 'ToothCareController',
                'toothCares' => $listToothCare,
            ]);
        }
    }

    /**
     * @Route("/add", name="add")
     * @Route("/edit/{id}", name="edit")
     */
    public function addToothCare(ToothCare $toothCare = null , Request $request)
    {
     
        if(!$toothCare){
            $toothCare = new ToothCare() ;
        }
        
        $form = $this->createForm(ToothCareType::class , $toothCare);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->em->persist($toothCare);
            $this->em->flush();
            
            return $this->redirectToRoute('toothcare.list');
            
            $this->addFlash('success', 'Medecin mise à jour avec succès');

        }

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/tooth_care/addToothCare.html.twig', [
                'controller_name'   => 'ToothCareController',
                'formToothCare'     => $form->createView(),
                'editMode'          => $toothCare->getId() != null ,
            ]);
        }
    }

    /**
     * @Route("/add/{adherent}", name="add_adherent")
     */
    public function toothCare(Request $request, Adherent $adherent , ToothCare $toothCare = null): Response
    {
        if(!$toothCare){

            $toothCare = new ToothCare();
            
        }

        $form = $this->createForm(ToothCareType::class, $toothCare);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($adherent){
                $toothCare->setAdherent($adherent);
                // $toothCare->setDate(new \DateTime());
            }
            $this->em->persist($toothCare);
            $this->em->flush();
            return $this->redirectToRoute('toothcare.list');
        }
        
        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/tooth_care/addToothCare.html.twig', [
                'formToothCare' => $form->createView(),
                'editMode' => $toothCare->getId()
            ]);
        }
    }

    /**
     * @Route("/edit", name="edit_edrhu")
     */
    public function edit(){

    }

    public function listRoles()
    {
        $listRoles = ["ROLE_MEDC" , "ROLE_INF" , "ROLE_ADMIN"] ;

        return $listRoles ;

    }

}
