<?php

namespace App\Controller;

use App\Form\EditType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditInformationController extends AbstractController
{
    #[Route('/employe/modifier/profil', name: 'edit_profile')]
    public function edit_profile(Request $request, ManagerRegistry $doctrine): Response
    {   $user = $this->getUser();
        $response="";
        $entityManager= $doctrine->getManager();
        $form=$this->createForm(EditType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {   $entityManager->persist($user);
            $entityManager->flush();
            $response="Mise à jour";
        }
        else {
            $entityManager->refresh($user);
        }

        return $this->render('user/modifier.profil.html.twig', ['form'=>$form->createView(),'response'=>$response]);
    }
}
