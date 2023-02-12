<?php

namespace App\Controller\admin;

use App\Entity\Family;
use App\Entity\Worker;
use App\Entity\Adherent;
use App\Form\FamilyType;
use App\Form\WorkerType;
use App\Form\AdherentType;
use App\Entity\Consultation;
use App\Services\DateService;
use App\Form\AdherentFamilyType;
use App\Form\AdherentWorkerType;
use App\Services\AdherentService;
use App\Services\RedirectService;
use App\Repository\FamilyRepository;
use App\Services\TraitementMatricule;
use App\Repository\AdherentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/adherent" , name ="adherent.")
*/
class AdherentController extends AbstractController
{
    protected $em;
    protected $adherentService;
    protected $traitementMatricule;
    protected $adherentRepository;
    protected $dateService;
    protected $session;
    public function __construct(EntityManagerInterface $em, DateService $dateService, 
    AdherentService $adherentService, TraitementMatricule $traitementMatricule, 
    AdherentRepository $adherentRepository , RedirectService $redirectService)
    {
        $this->em                   = $em;
        $this->adherentService      = $adherentService;
        $this->traitementMatricule  = $traitementMatricule;
        $this->adherentRepository   = $adherentRepository;
        $this->dateService          = $dateService;
        $this->redirectService      = $redirectService;
        $this->session              = new Session();
    }
    /**
     * @Route("/", name="list")
     */
    public function adherentsList(): Response
    {
        $listFamilies   = $this->em->getRepository(Adherent::class)->findBy(['category' => "002" ]);
        
        foreach($listFamilies as $family){
            if($this->dateService->dateDiffefence($family->getBirthday())->y >= 18){
                $family->setStatus(false);
                $this->em->persist($family);    
            }
        }
        $this->em->flush();
        $listAdherents   = $this->em->getRepository(Adherent::class)->findAll();
        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
        // $listAdherents  = array_merge($listWorkers, $listFamilies);
            return $this->render('admin/adherent/list.html.twig', [
                'controller_name'   => 'AdherentController',
                'listAdherents'     => $listAdherents
            ]);
        }
    }

    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detailAdherent(Adherent $adherent = null)
    {
        if($adherent->getCategory() != '000'){
            $listFamilies = $adherent->getWorker();
        }
        switch ($adherent->getCategory()) {
            case '000':
                $listFamilies   = $this->em->getRepository(Adherent::class)->findBy(['worker' => $adherent ]);
                break;
            default:
                $listFamilies   = $this->adherentRepository->getFamilies($adherent);
                array_push($listFamilies, $adherent->getWorker());
                break;
        }
        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/adherent/detail.html.twig', [
                "adherent"  => $adherent ,
                "families"  => $listFamilies
            ]);
        }
    }

    /**
     * @Route("/edit/{type}/{id}", name="edit")
     * @Route("/add/{type}", name="add")
     */
    public function editAdherent(Request $request, Adherent $adherent = null, AdherentRepository $adherentRepository, $type = null)
    {
        $this->session->remove('id');
        $this->session->remove('position');
        $this->session->remove('establishment');
        if (!$adherent){
            $adherent       = new Adherent();
        }else{
            $this->session->set('id', $adherent->getId());
            $this->session->set('position', $adherent->getPosition() );
            $this->session->set('establishment', $adherent->getEstablishment() );
            $position = $this->session->get('position');
            $establishment = $this->session->get('establishment');
        }
        $this->session->set('type', $type);
        $order = $this->session->get('type');

        if($order =='worker'){
            $form = $this->createForm(AdherentWorkerType::class, $adherent);
        }else{
            $form = $this->createForm(AdherentFamilyType::class, $adherent);
        }
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if ( $form->get('avatar')->getData()) {
                if ($adherent) {
                    # code...
                    $filesystem = new Filesystem();

                //     if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                //         $url = "https"; 
                //     }
                //     else{
                //         $url = "http"; 
                //     }
            
                //     $url .= "://"; 
                //     $url .= $_SERVER['HTTP_HOST']; 
                    
                    // $filename =$url."/uploads/avatarAdherent/".$adherent->getAvatar() ;
                    $filename = $this->getParameter("avatarDirectory").'/'.$adherent->getAvatar() ;
                    
                    $filesystem->remove($filename);
                }
                $images= $form->get('avatar')->getData() ;
                # code...
                $file = explode('.',$images->getClientOriginalName());
                $filename = $file[0].''.uniqid().'.'.$images->guessExtension();

                $adherent->setAvatar($filename);


                $images->move($this->getParameter('avatarDirectory'), $filename);
            }
            if( !$this->session->get('id') ){
                switch ($order) {
                    case 'worker':
                        $listWorkersByEstablishment = $adherentRepository->getNumberWorkersByEstablishment($adherent->getEstablishment());
                        $total          = count($listWorkersByEstablishment);
                        $adherent->setStatus(true);
                        $adherent->setMatricule(''.$this->traitementMatricule->affiliateNumberGenerator($total)) ;
                        $adherent->setCategory('000');
                        $adherent->setAffiliatenumber(' '.$adherent->getEstablishment()->getCode().' - '.$adherent->getPosition()->getCode().' - '.$adherent->getMatricule().' - '.$adherent->getCategory());
                        break;

                    case 'family':
                            $adherent->setStatus(true);
                            $adherent->setMatricule($adherent->getWorker()->getMatricule());
                            if($adherent->getCategory() === '001'){
                                $adherent->setAffiliatenumber(''.$adherent->getWorker()->getEstablishment()->getCode().' - '.$adherent->getWorker()->getPosition()->getCode().' - '.$adherent->getWorker()->getMatricule().' - '.$adherent->getCategory());
                            }else{
                                $count = $adherentRepository->getNumberChildMember($adherent->getWorker());
                                $count = count($count) + 1;
                                $adherent->setAffiliatenumber(''.$adherent->getWorker()->getEstablishment()->getCode().' - '.$adherent->getWorker()->getPosition()->getCode().' - '.$adherent->getWorker()->getMatricule().' - '.$this->traitementMatricule->affiliateNumberGenerator($count));
                            }
                        break;
                        
                    default:
                        break;
                }
                
            }else{
                if( $adherent->getPosition() != $position || $adherent->getEstablishment() != $establishment ){
                    $adherent->setAffiliatenumber(' '.$adherent->getPosition()->getCode().' - '.$adherent->getEstablishment()->getCode().' - '.$adherent->getMatricule().' - '.$adherent->getCategory());
                }
            }
            $this->em->persist($adherent);
            $this->em->flush();
            return $this->redirectToRoute('adherent.list');
        }
        if(($this->redirectService->redirectToHome($this->listRoles()) ==true)) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            if($order =='worker'){
                return $this->render('admin/adherent/addAdherentWorker.html.twig', [
                    "formAdherent"  => $form->createView(),
                    "editMode"      => $adherent->getId() != null ,
                    "adherent"      => $adherent ,
                    "type"          => $type
                    
                ]);
            }else{
                return $this->render('admin/adherent/addAdherentFamily.html.twig', [
                    "formAdherent"  => $form->createView(),
                    "editMode"      => $adherent->getId() != null ,
                    "adherent"      => $adherent ,
                    "type"          => $type , 
                    
                ]);
            }
        }
    }

    /**
     * @Route("/medicalpath/{id}", name="medicalpath")
     */
    public function medicalPath(Adherent $adherent)
    {
        $consultations = $this->em->getRepository(Consultation::class)->findBy(['adherent' => $adherent] , ['id' => 'DESC']) ;

        if(($this->redirectService->redirectToHome($this->listRolesMedc()) ==true)) {

            return $this->redirectToRoute('dashboard') ;

        }else {
            return $this->render('admin/adherent/medicalPath.html.twig', [
                "adherent"          => $adherent ,
                "consultations"     => $consultations ,
            ]);
        }
    }

    /**
     * @Route("/status/{id}", name="status")
     */
    public function adherentStatusChange(Request $request, Adherent $adherent){
        if( $adherent->getCategory() == '000'){
            switch ($adherent->getStatus()) {
                case true:
                    $this->adherentService->changeFamiliesStatus($adherent);
                    $adherent->setStatus(false);
                    break;
                
                case false:
                    $this->adherentService->changeFamiliesStatus($adherent);
                    $adherent->setStatus(true);
                    break;
                default:
                    break;
            }
        }else{
            switch ($adherent->getStatus()) {
                case true:
                    $adherent->setStatus(false);
                    break;
                
                case false:
                    $adherent->setStatus(true);
                    break;
                default:
                    break;
            }
        }
        $this->em->persist($adherent);
        $this->em->flush();
        return $this->redirectToRoute('adherent.list');
    }

    public function listRoles()
    {
        $listRoles = ["ROLE_ADMIN" , "ROLE_MEDC" , "ROLE_INF" , "ROLE_HOME_ACCOUNT"] ;

        return $listRoles ;

    }

    public function listRolesMedc()
    {
        $listRoles = ["ROLE_MEDC"] ;

        return $listRoles ;

    }

}