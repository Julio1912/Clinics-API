<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Entity\Family;
use App\Entity\Worker;
use App\Entity\Adherent;
use App\Entity\Drug;
use App\Entity\Position;
use App\Entity\Establishment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/admin")
*/
class DashboardController extends AbstractController
{
    protected $em ;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/", name="dashboard")
     */
    public function index(): Response
    {
        $establishement = $this->em->getRepository(Establishment::class)->findAll() ;
        $position       = $this->em->getRepository(Position::class)->findAll() ;
        $worker         = $this->em->getRepository(Worker::class)->findAll() ;
        $adherent       = $this->em->getRepository(Adherent::class)->findBy(['status' => true]) ;
        $drugStores     = $this->em->getRepository(Drug::class)->findAll() ;
        $physicians     = $this->em->getRepository(User::class)->findByRoleMedc() ;

        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name'   => 'DashboardController',
            'establishement'    => count($establishement),
            'position'          => count($position),
            'adherent'          => count($adherent),
            'physician'         => count($physicians),
            'drugStores'        => count($drugStores),
        ]);
    }
}
