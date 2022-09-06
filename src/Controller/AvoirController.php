<?php

namespace App\Controller;

use App\Entity\Avoir;
use App\Entity\Vente;
use App\Form\AvoirType;
use App\Form\EntreeType;
use App\Repository\ArticleRepository;
use App\Repository\AvoirRepository;
use App\Repository\VenteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvoirController extends AbstractController
{
    #[Route('/employe/ajouter/avoir', name: 'add_avoir')]
    public function ajouter_avoir(Request $request , EntityManagerInterface $entityManager,VenteRepository $vente,ArticleRepository $art): Response
    {   $avoir = new Avoir();
        $success="";
        $error="";
        $form=$this->createForm(AvoirType::class,$avoir);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
         $id_vente=$form->get('vente')->getData();
         $v=$vente->find($id_vente);
         $id_article=$v->getArticle();
         $qte_rendre=$v->getQteC();
         $article=$art->find($id_article);
         if($v->isAvoir() == false)
         {
             $article->setQte($article->getQte() + $qte_rendre);
             $v->setAvoir(True);
             $entityManager->persist($article);
             $entityManager->persist($avoir);
             $entityManager->persist($v);
             $entityManager->flush();
             $success="Avoir ajouté avec succès";
         }
         else {
             $error="cette vente est dèjà associé à un avoir";
         }

        }
        return $this->render('avoir/ajouter.avoir.html.twig', ['form'=>$form->createView(),
            'success'=>$success,'error'=>$error
        ]);
    }
    #[Route('employe/lister/avoirs',name : 'avoir_list')]
   public function lister_avoir(AvoirRepository $avoir) : Response {
        $avoirs = $avoir->findAll();
        return $this->render('avoir/liste.avoir.html.twig',['avoirs'=>$avoirs]);
    }

    #[Route('/employe/supprimer/avoir/{id}',name : 'delete_avoir')]
   public function delete_avoir(Avoir $avoir,EntityManagerInterface $entityManager) : Response
    {
        $article=$avoir->getVente()->getArticle();
        $qte=$avoir->getVente()->getQteC();
        $article->setQte($article->getQte() - $qte);
        $vente=$avoir->getVente();
        $vente->setAvoir(false);
        $entityManager->persist($article);
        $entityManager->remove($avoir);
        $entityManager->persist($vente);
        $entityManager->flush();
        return $this->redirectToRoute('avoir_list');

    }
 #[Route('/employe/modifier/avoir/{id}',name : 'modifier_avoir')]
public function modifier_avoir(Avoir $avoir,VenteRepository $vente,Request $request,ArticleRepository $art,EntityManagerInterface $entityManager) : Response {
        $form=$this->createForm(AvoirType::class,$avoir);
        $success="";
        $error="";
        $id_avant=$form->get('vente')->getData();
        $vente_avant=$vente->find($id_avant);
        $a=$avoir->getVente()->getArticle();
        $article_avant=$art->find($a);
        $qte_avant=$vente_avant->getQteC();
     $form->handleRequest($request);
     if($form->isSubmitted() && $form->isValid())
     { $id=$form->get('vente')->getData();
         if($id == $id_avant)
         { $success="Mise à jour avec succès";
         }
         else {
             $v=$vente->find($id);
             if($v->isAvoir() == false) {
                 $a=$v->getArticle();
                 $article=$art->find($a);
                 $vente_avant->setAvoir(false);
                 $v->setAvoir(true);
                 $article_avant->setQte($article_avant->getQte() - $qte_avant );
                 $article->setQte($article->getQte() + $v->getQteC());
                 $entityManager->persist($v);
                 $entityManager->persist($vente_avant);
                 $entityManager->persist($article_avant);
                 $entityManager->persist($article);
                 $entityManager->flush();
                 $success="Mise à jour avec succès";
             }
             else {
                 $error="cette vente est dèjà associé à un avoir";
             }

         }
     }



        return $this->render('avoir/modifier.avoir.html.twig',['form'=>$form->createView(),'success'=>$success,'error'=>$error]);
 }

}
