<?php 
namespace App\Services;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\RouterInterface;

class RedirectService extends AbstractController
{
    protected $router ;
    public function __construct(RouterInterface $router)
    {

        $this->router = $router ;

    }
    public function redirectToHome(array $params = [])
    {

        $redirect = true ;
        
        foreach ($params as $param) {
            # code...
            if(in_array($param , $this->getUser()->getRoles())){
                
                $redirect = false ;
                
            }
        }

        return $redirect ;
       
    }

}