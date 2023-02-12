<?php

namespace App\Controller;

use App\Entity\Diagnostic;
use App\Entity\Drug;
use App\Entity\ToothCare;
use App\Entity\User;
use App\Entity\WorkIncidentLesion;
use App\Repository\UserRepository;
use App\Services\TraitementData;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminAuthenticator extends AbstractController
{
    protected $em;
    protected $traitementData;

    public function __construct(TraitementData $traitementData , EntityManagerInterface $em )
    {
        $this->em               = $em;
        $this->traitementData   = $traitementData;
    }

    /**
     * @Route("/admin/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $userRepository , UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername   = $authenticationUtils->getLastUsername();

        $admin          = $userRepository->findByroleadmin();

        $drugStore      = $userRepository->findByroleDrugstore() ;

        $home           = $userRepository->findByroleHommeAccount() ;

        $lesionOther    = $this->em->getRepository(WorkIncidentLesion::class)->findOneBy(['name' => "Autres"]) ; 

        

        if (!$lesionOther) {
            # code...
            $lesionOther =  new WorkIncidentLesion() ;
            $lesionOther->setName("Autres")
                        ->setDescription("Tous autres natures de lésions accident de travails")
                        ->setStatus(true) ;
            $this->em->persist($lesionOther) ;
        }

        $drugsOther    = $this->em->getRepository(Drug::class)->findOneBy(['name' => "Autres"]) ; 

        if (!$drugsOther) {
            # code...
            $drugsOther =  new Drug() ;
            $drugsOther->setName("Autres")
                        ->setType("AMP")
                        ->setQuantity(10000) ;
            $this->em->persist($drugsOther) ;
        }

        $entityManager  = $this->getDoctrine()->getManager();


        // creation admin par defaut
        if(!$admin){
            $user = new User();

            // $Utilisateur->setStatus(1);
            $user->setRoles(array('ROLE_ADMIN'));
            $user->setEmail('admin@smisa.com');
            $user->setName('Admin');
            $user->setFirstname('Smisa');
            $passwordcyrpter = $passwordEncoder->encodePassword($user, 'adminsmisa');
            $user->setPassword($passwordcyrpter);
            $entityManager->persist($user);
        }

        if(!$drugStore){
            $user = new User();

            // $Utilisateur->setStatus(1);
            $user->setRoles(array('ROLE_DRUG_STORE'));
            $user->setEmail('pharmacie@smisa.com');
            $user->setName('Pharmacie');
            $user->setFirstname('Smisa');
            $passwordcyrpter = $passwordEncoder->encodePassword($user, 'pharmaciesmisa');
            $user->setPassword($passwordcyrpter);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
        }

        if(!$home){
            $user = new User();

            // $Utilisateur->setStatus(1);
            $user->setRoles(array('ROLE_HOME_ACCOUNT'));
            $user->setEmail('accueil@smisa.com');
            $user->setName('Home');
            $user->setFirstname('Smisa');
            $passwordcyrpter = $passwordEncoder->encodePassword($user, 'accueilsmisa');
            $user->setPassword($passwordcyrpter);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
        }


        $datasDiagnostics  = $this->traitementData->listDiagnostic() ;
        $datasToothCares   = $this->traitementData->listToothCare() ;

        foreach ($datasDiagnostics as $datasDiagnostic) {
            # code...
            $checkDiagnostics = $this->em->getRepository(Diagnostic::class)->findOneBy(['name' => $datasDiagnostic['name']]) ;

            if(!$checkDiagnostics || empty($checkDiagnostics))
            {

                $diagnostic = new Diagnostic() ;
            
                $diagnostic->setName($datasDiagnostic['name'])
                            ->setDescription($datasDiagnostic['name'])
                            ;
                            // ->setStatus(true) ;

                $this->em->persist($diagnostic);
                

            }
        }

        foreach ($datasToothCares as $datasToothCare) {
            # code...
            $checkDiagnostics = $this->em->getRepository(ToothCare::class)->findOneBy(['name' => $datasToothCare['name']]) ;

            if(!$checkDiagnostics || empty($checkDiagnostics))
            {

                $toothCare = new ToothCare() ;
            
                $toothCare->setName($datasToothCare['name'])
                            ->setStatus(true) 
                            ;

                $this->em->persist($toothCare);
                
            }
        }
        
        $entityManager->flush();


        return $this->render('security/admin/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/admin/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
