<?php 
namespace App\Services;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

Class DateService extends AbstractController{
    
    public function dateDiffefence(DateTime $birthday){
        return date_diff($birthday, new DateTime());
        // ->format('Y-m-d H:i:s')
    }

    public function traitementDifferenceDate($birhday)
    {
        # code...
        $today      = json_decode(json_encode(new DateTime()) , true)['date']  ;
        $birthday   = json_decode(json_encode($birhday) , true)['date'] ;

        $diff   = abs(strtotime($today) - strtotime($birthday)) ;

        $years  = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days   = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 *24) / (60 * 60 * 24));
        
        return new JsonResponse([
            'years'     => $years ,
            'months'    => $months ,
            'days'      => $days ,
        ]) ;

    }

}