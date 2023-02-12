<?php

namespace App\Controller\admin;

use App\Entity\Position;
use App\Form\PositionType;
use App\Services\RedirectService;
use App\Services\TraitementMatricule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/position" , name ="position.")
*/
class PositionController extends AbstractController
{
    protected $em;
    protected $redirectService;
    protected $traitementMatricule;
    public function __construct(EntityManagerInterface $em , TraitementMatricule $traitementMatricule ,
    RedirectService $redirectService)
    {
        $this->em                   = $em;
        $this->redirectService      = $redirectService;
        $this->traitementMatricule  = $traitementMatricule;
    }

    /**
     * @Route("/", name="list")
     */
    public function index(): Response
    {
        $listPositions = $this->em->getRepository(Position::class)->findAll() ;
        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/position/list.html.twig', [
                'controller_name'   => 'PositionController',
                'listPositions'     => $listPositions ,
            ]);
        }
    }

    /**
     * @Route("/add", name="add")
     * @Route("/edit/{id}", name="edit")
     */
    public function newPosition(Request $request, Position $position = null){
        if(!$position){
            $position = new Position();
            $listPositions = $this->em->getRepository(Position::class)->findAll() ;
            $total = count($listPositions) ;
            $generateNumber = $this->traitementMatricule->affiliateNumberGenerator($total) ;
            $position->setCode($generateNumber) ;
        }

        $form = $this->createForm(PositionType::class, $position);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($position);
            $this->em->flush();
            return $this->redirectToRoute('position.list');
        }

        if ($this->redirectService->redirectToHome($this->listRoles()) ==true) {

            return $this->redirectToRoute('dashboard') ;
            // $this->redirectService->redirectToHome(["ROLE_DRUG_STORE" , "ROLE_ADMIN"]) ;

        }else {
            return $this->render('admin/position/add.html.twig',[
                "formPosition"  => $form->createView() ,
                "editMode"      => $position->getId() != null ,
            ]);
        }
    }

    public function listRoles()
    {
        $listRoles = ["ROLE_ADMIN", "ROLE_HOME_ACCOUNT"] ;

        return $listRoles ;

    }

}
