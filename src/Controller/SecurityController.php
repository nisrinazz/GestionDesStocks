<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;


class SecurityController extends AbstractController
{
    #[Route('/admin/ajouter/employe',name:'add_emp')]
public function add_emp(Request $request , ManagerRegistry $doctrine ,UserPasswordHasherInterface $passwordHasher) : Response {
        $user = new User();
        $entityManager= $doctrine->getManager();
        $success="";
        $form =$this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {   $pass=$user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $pass
            );
            $user->setPassword($hashedPassword);
            $entityManager->persist($user);
            $success="Ajouté avec succès";
            $entityManager->flush();

        }
      return $this->render('employe/ajouter.employe.html.twig',['form'=>$form->createView(),'success'=>$success]);
    }

    #[Route('/admin/supprimer/employe/{id}',name:'delete_emp')]
    public function delete_emp(ManagerRegistry $doctrine,User $user) : Response
    {       $entityManager=$doctrine->getManager();
            if($user->isGranted('ROLE_EMPLOYEE'))
            {
                $entityManager->remove($user);
                $entityManager->flush();

            }
            return $this->redirectToRoute('employees_list');
        }





}
