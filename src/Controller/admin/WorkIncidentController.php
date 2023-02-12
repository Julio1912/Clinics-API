<?php

namespace App\Controller\admin;

use App\Entity\Adherent;
use App\Entity\WorkIncident;
use App\Entity\WorkIncidentLesion;
use App\Form\WorkIncidentLesionType;
use App\Form\WorkIncidentType;
use App\Services\RedirectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/incident" , name ="incident.")
*/
class WorkIncidentController extends AbstractController
{
    protected $em;
    protected $redirectService;
    public function __construct(EntityManagerInterface $em , RedirectService $redirectService)
    {
        $this->em               = $em;
        $this->redirectService  = $redirectService;
    }

    /**
     * @Route("/lesion", name="lesion.list")
     */
    public function incidentList(): Response
    {
        $listsLesions = $this->em->getRepository(WorkIncidentLesion::class)->findAll();

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/work_incident/listLesion.html.twig', [
                'controller_name' => 'WorkIncidentController',
                'listsLesions' => $listsLesions
            ]);
        }
    }

     /**
     * @Route("/lesion/add", name="lesion.add")
     * @Route("/lesion/edit/{id}", name="lesion.edit")
     */
    public function incidentAdd(WorkIncidentLesion $workIncidentLesion = null , Request $request): Response
    {
        if ($workIncidentLesion == null) {
            # code...
            $workIncidentLesion = new WorkIncidentLesion() ;
        }

        $form = $this->createForm(WorkIncidentLesionType::class, $workIncidentLesion);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->em->persist($workIncidentLesion);
            $this->em->flush();
            return $this->redirectToRoute('incident.lesion.list');

        }

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/work_incident/addLesion.html.twig', [
                'controller_name' => 'WorkIncidentController',
                'formLesion'      => $form->createView() ,
                'editMode' => $workIncidentLesion->getId() != null ,
            ]);
        }
    }


    /**
     * @Route("/", name="list")
     */
    public function index(): Response
    {
        $listIncident = $this->em->getRepository(WorkIncident::class)->findAll();

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/work_incident/listWorkIncident.html.twig', [
                'controller_name' => 'WorkIncidentController',
                'incidents' => $listIncident
            ]);
        }
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function incident(Request $request, WorkIncident $workIncident = null , Adherent $adherent = null): Response
    {
        if(!$workIncident){

            $workIncident = new WorkIncident();

        }
        if(!$adherent){

            $adherent = new Adherent();

        }
        
        $form = $this->createForm(WorkIncidentType::class, $workIncident);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($adherent){
                $workIncident->setAdherent($adherent);
                $workIncident->setEstablishment($adherent->getEstablishment());
                $workIncident->setPosition($adherent->getPosition());
                // $workIncident->setDate(new \DateTime());
            }
            // d    d($workIncident);
            $this->em->persist($workIncident);
            $this->em->flush();
            return $this->redirectToRoute('incident.list');
        }

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/work_incident/addWorkIncident.html.twig', [
                'formWorkIncident' => $form->createView(),
                'editMode' => $workIncident->getId() ,
                'adherent' => $adherent ,
            ]);
        }
    }

    public function listRoles()
    {
        $listRoles = ["ROLE_MEDC" , "ROLE_INF" , "ROLE_ADMIN"] ;

        return $listRoles ;

    }


    
}
