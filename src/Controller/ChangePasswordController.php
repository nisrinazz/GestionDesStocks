<?php

namespace App\Controller;

use App\Entity\ChangePassword;
use App\Form\ResetPasswordType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class ChangePasswordController extends AbstractController
{
    #[Route('/employe/changer/pwd', name: 'change_password')]
    public function change_pass(ManagerRegistry $doctrine,Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {  $entityManager = $doctrine->getManager();
        $user=$this->getUser();
        $success="";
        $changePassword = new ChangePassword();
        $form=$this->createForm(ResetPasswordType::class,$changePassword);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $new=$form->get('password')->getData();
            $newEncodedpass=$passwordHasher->hashPassword($user,$new);
            $user->setPassword($newEncodedpass);
            $entityManager->flush();
            $success="Mise à jour";
        }
        return $this->render('user/change.password.html.twig',['form'=>$form->createView(),'success'=>$success]);
    }
}
