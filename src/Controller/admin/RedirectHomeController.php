<?php

namespace App\Controller\admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RedirectHomeController extends AbstractController
{

    /**
     * @Route("/", name="redirect")
     */
    public function index()
    {
        return $this->redirectToRoute('dashboard') ;
    }

}