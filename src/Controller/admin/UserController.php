<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\UpdatePasswordType;
use App\Services\TraitementMatricule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user" , name ="admin.")
*/
class UserController extends AbstractController
{
    protected $em;
    protected $traitementMatricule;
    public function __construct(EntityManagerInterface $em , TraitementMatricule $traitementMatricule)
    {
        $this->em = $em;
        $this->traitementMatricule = $traitementMatricule;
    }
    /**
     * @Route("/update/password/{id}", name="updatePassword")
     */
    public function updatePassword(User $user = null , Request $request , UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if(!$user)
        {
            $user = new User() ;
        }
        $form = $this->createForm(UpdatePasswordType::class , $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $passwordCyrpter = $passwordEncoder->encodePassword($user, $form->get('password')->getData());

            $user->setPassword($passwordCyrpter) ;

            $this->em->persist($user);
            $this->em->flush();
            
            return $this->redirectToRoute('dashboard');
            
            $this->addFlash('success', 'Medecin mise à jour avec succès');

        }

        return $this->render('admin/user/index.html.twig', [
            'controller_name'       => 'UserController',
            'formUpdatePassword'   => $form->createView() ,
        ]);
    }

    /**
     * @Route("/physician/add", name="physician.add")
     */
    public function physicianAdd(): Response
    {
        
        return $this->render('/user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

}
