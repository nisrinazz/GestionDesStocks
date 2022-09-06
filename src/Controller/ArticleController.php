<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;

use App\Repository\ArticleRepository;
use App\Repository\VenteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/employe/ajouter/article', name: 'add_art')]
    public function add_article(ManagerRegistry $doctrine,Request $request): Response
    { $article = new Article();
        $response="";
        $form=$this->createForm(ArticleType::class,$article);
        $entityManager=$doctrine->getManager();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($article);
            $entityManager->flush();
            $response="Ajouté avec succès";
        }
        return $this->render('article/ajouter.article.html.twig',['form'=>$form->createView(),'response'=>$response]);
    }

    #[Route('/employe/lister/articles',name : 'art_list')]
    public function list_article(ArticleRepository $article , VenteRepository $vente) : Response
    {   $art = $article->findAll();
        return $this->render('article/liste.article.html.twig',["articles"=>$art]);
    }


    #[Route('employe/supprimer/article/{id}',name: 'delete_article')]
    public function delete_article(Article $article , EntityManagerInterface $entityManager) : Response {
        $response="";

            $entityManager->remove($article);
            $entityManager->flush();


        return $this->redirectToRoute('art_list');
    }
   #[Route('employe/modifier/article/{id}' , name : 'modifier_article' )]
    public function modifier_article(Article $article,EntityManagerInterface $entityManager,Request $request) : Response {
        $form=$this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        $response="";
        if($form->isSubmitted() && $form->isValid())
        {   $entityManager->persist($article);
            $entityManager->flush();
            $response="Mis à jour avec succés";
        }

        return $this->render('article/modifier.article.html.twig',['form'=>$form->createView(),'response'=>$response]);
   }
   #[Route('/employe/rupture/stock',name:'rupture_stock')]
  public function rupture_stock(ArticleRepository $article): Response {
        $articles=$article->findBy(['qte'=>0]);
        return $this->render('article/rupture.stock.html.twig',['articles'=>$articles]);
   }

}
