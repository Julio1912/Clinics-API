<?php

namespace App\Controller\admin;

use DateTime;
use App\Entity\User;
use App\Form\UserType;
use App\Form\PhysicianType;
use App\Form\UpdatePasswordType;
use App\Services\RedirectService;
use App\Services\TraitementMatricule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/physician" , name ="physician.")
*/
class PhysicianController extends AbstractController
{
    protected $em;
    protected $passwordEncoder;
    protected $redirectService;
    protected $traitementMatricule;
    public function __construct(EntityManagerInterface $em , TraitementMatricule $traitementMatricule
     , UserPasswordEncoderInterface $passwordEncoder , RedirectService $redirectService)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->redirectService = $redirectService;
        $this->traitementMatricule = $traitementMatricule;
    }
    
    /**
     * @Route("/", name="list")
     */
    public function list(): Response
    {
        $listPhysicians = $this->em->getRepository(User::class)->findByRoleMedc() ;
        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/physician/list.html.twig', [
                'controller_name'   => 'PhysicianController',
                'listPhysicians'    => $listPhysicians,
            ]);
        }
    }


    /**
     * @Route("/add", name="add")
     * @Route("/edit/{id}", name="edit")
     */
    public function add(User $physician = null , Request $request): Response
    {
        
        if(!$physician){
            $physician      = new User();
            $listPhysician  = $this->em->getRepository(User::class)->findByRoleMedc() ;
            $total          = count($listPhysician) ;
            $generateNumber = $this->traitementMatricule->affiliateNumberGenerator($total) ;
        }
        
        $form = $this->createForm(PhysicianType::class, $physician);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            if ($physician->getId() == null) {
                $physician->setAffiliate($form->get('type_med')->getData().$generateNumber) ;
                $physician->setPassword($this->passwordEncoder->encodePassword($physician , $form->get('password')->getData())) ;
                $physician->setEmail($form->get('type_med')->getData().$generateNumber) ;
            }
            $physician->setRoles(["ROLE_MEDC"]) ;
            $physician->setCreated(new \DateTime()) ;
            $physician->setTypeMed($form->get('type_med')->getData()) ;
            
            
            $this->em->persist($physician);
            $this->em->flush();

            return $this->redirectToRoute('physician.list');

            $this->addFlash('success', 'Medecin mise à jour avec succès');

        }
        if ($this->redirectService->redirectToHome($this->listRoles()) == true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/physician/add.html.twig', [
                'controller_name'   => 'PhysicianController',
                'formPhysician'     => $form->createView() ,
                'editMode'          => $physician->getId() != null ,
                'physician'         => $physician ,
            ]);
        }
    }

     /**
     * @Route("/password/update/{id}", name="updatepassword")
     */
    public function updatePassword(Request $request , User $physician): Response
    {
        $form = $this->createForm(UpdatePasswordType::class , $physician);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $physician->setPassword($this->passwordEncoder->encodePassword($physician , $form->get('password')->getData())) ;
            
            $this->em->persist($physician);
            $this->em->flush();
            
            return $this->redirectToRoute('physician.list');
            
            $this->addFlash('success', 'Medecin mise à jour avec succès');

        }
        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/physician/updatePassword.html.twig', [
                'controller_name'       => 'PhysicianController',
                'formUpdatePassword'    => $form->createView() ,

            ]);
        }
    }

    public function listRoles()
    {
        $listRoles = ["ROLE_ADMIN"] ;

        return $listRoles ;

    }

}
