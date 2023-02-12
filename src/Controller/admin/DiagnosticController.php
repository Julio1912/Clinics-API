<?php

namespace App\Controller\admin;

use App\Entity\Diagnostic;
use App\Form\DiagnosticType;
use App\Services\RedirectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/diagnostic" , name ="diagnostic.")
*/
class DiagnosticController extends AbstractController
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
    public function diagnosticList(): Response
    {
        $listDiagnostic = $this->em->getRepository(Diagnostic::class)->findAll();

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/diagnostic/listDiagnostic.html.twig', [
                'controller_name' => 'DiagnosticController',
                'diagnostics' => $listDiagnostic
            ]);
        }
    }

    /**
     * @Route("/add", name="add")
     * @Route("/edit/{id}", name="edit")
     */
    public function nouveauDiagnostique(Request $request, Diagnostic $diagnostic = null): Response
    {
        if(!$diagnostic){
            $diagnostic = new Diagnostic();
        }
        $form = $this->createForm(DiagnosticType::class, $diagnostic);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($diagnostic);
            $this->em->flush();
            return $this->redirectToRoute('diagnostic.list');
        }
        // $listDiagnostic = $this->em->getRepository(Diagnostic::class)->findAll();

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/diagnostic/addDiagnostic.html.twig', [
                "formDiagnostic" => $form->createView(),
                "editMode" => $diagnostic->getId()
            ]);
        }
    }

    public function listRoles()
    {
        $listRoles = ["ROLE_MEDC" , "ROLE_INF" , "ROLE_ADMIN"] ;

        return $listRoles ;

    }

}
