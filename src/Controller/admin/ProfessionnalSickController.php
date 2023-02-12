<?php

namespace App\Controller\admin;

use App\Entity\Adherent;
use App\Entity\ProfessionnalSick;
use App\Form\ProfessionnalSickType;
use App\Services\RedirectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sick" , name ="sick.")
*/
class ProfessionnalSickController extends AbstractController
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
        $listSick = $this->em->getRepository(ProfessionnalSick::class)->findAll();

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/professionnal_sick/listProfessionnalSick.html.twig', [
                'controller_name' => 'ProfessionnalSickController',
                'sicks' => $listSick
            ]);
        }
    }

    /**
     * @Route("/add/{adherent}", name="add")
     */
    public function sick(Request $request, ProfessionnalSick $professionnalSick = null, Adherent $adherent = null): Response
    {
        if(!$professionnalSick){

            $professionnalSick = new ProfessionnalSick();

        }
        
        $form = $this->createForm(ProfessionnalSickType::class, $professionnalSick);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($adherent){
                $professionnalSick->setAdherent($adherent);
                // $professionnalSick->setDate(new \DateTime());
            }
            $this->em->persist($professionnalSick);
            $this->em->flush();
            return $this->redirectToRoute('sick.list');
        }

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/professionnal_sick/addProfessionnalSick.html.twig', [
                'formProfessionnalSick' => $form->createView(),
                'editMode' => $professionnalSick->getId()
            ]);
        }
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(){

    }
    

    public function listRoles()
    {
        $listRoles = ["ROLE_MEDC" , "ROLE_INF" , "ROLE_ADMIN"] ;

        return $listRoles ;

    }

}