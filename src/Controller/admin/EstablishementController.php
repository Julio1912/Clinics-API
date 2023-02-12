<?php

namespace App\Controller\admin;

use App\Entity\Adherent;
use App\Entity\Establishment;
use App\Form\EstablishmentType;
use App\Services\AdherentService;
use App\Services\RedirectService;
use App\Services\TraitementMatricule;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/establishement" , name ="establishement.")
*/
class EstablishementController extends AbstractController
{
    protected $em;
    protected $redirectService;
    protected $adherentService;
    protected $traitementMatricule;
    public function __construct(EntityManagerInterface $em , TraitementMatricule $traitementMatricule,
     AdherentService $adherentService , RedirectService $redirectService)
    {
        $this->em = $em;
        $this->redirectService = $redirectService;
        $this->adherentService = $adherentService;
        $this->traitementMatricule = $traitementMatricule;
    }
    
    /**
     * @Route("/", name="list")
     */
    public function list(): Response
    {
        $listEstablishements     = $this->em->getRepository(Establishment::class)->findAll() ;

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/establishement/list.html.twig', [
                'listEstablishements'   => $listEstablishements ,
                'controller_name'       => 'EstablishementController',
            ]);
        }
    }

     /**
     * @Route("/add", name="add")
     * @Route("/edit/{id}", name="edit")
     */
    public function newEshtablishement(Request $request , Establishment $establishment=null){
       
        if(!$establishment){
            $establishment       = new Establishment();
            $listEstablishements = $this->em->getRepository(Establishment::class)->findAll() ;
            $total               = count($listEstablishements) ;
            $generateNumber      = $this->traitementMatricule->affiliateNumberGenerator($total) ;
            $establishment->setCode($generateNumber) ;
        }
        $_SESSION['status'] = $establishment->getStatus();
        $form = $this->createForm(EstablishmentType::class, $establishment);
        $form->handleRequest($request);
        // dd($establishment->getStatus());
        if($form->isSubmitted() && $form->isValid()){
            $establishment->setCreated(new \DateTime()) ;
            
            if ($establishment->getId() != 0 ) {
                if($establishment->getStatus() != $_SESSION['status']){
                    // dd('action vaovao') ;
                    $workers = $establishment->getWorkers();
                    foreach($workers as $worker){
                        if($worker->getStatus() === true){
                            $this->adherentService->changeFamiliesStatus($worker);
                            $worker->setStatus(false);
                        }
                    }
                    // dd($establishment->getStatus());
                    // $establishment->setStatus(!$establishment->getStatus());
                    
                }
            }
            $this->em->persist($establishment);
            

            $this->em->flush();
            return $this->redirectToRoute('establishement.list');
        }

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/establishement/add.html.twig',[ 
                "formEstablishement"    => $form->createView() ,
                "editMode"              => $establishment->getId() != null ,
            ]);
        }
    }

    public function AdherentPerEstablishement($establishment)
    {

        $adherent = $this->em->getRepository(Adherent::class)->findBy(['establishment' => $establishment , 'category' =>"000"]) ;

        return new Response(count($adherent)) ;

    }

    public function listRoles()
    {
        $listRoles = ["ROLE_ADMIN" , "ROLE_HOME_ACCOUNT"] ;

        return $listRoles ;

    }
}
