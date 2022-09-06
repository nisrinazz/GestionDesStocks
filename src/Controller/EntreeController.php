<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Form\EntreeType;
use App\Repository\ArticleRepository;
use App\Repository\EntreeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntreeController extends AbstractController
{
    #[Route('/employe/ajouter/entree', name: 'add_entree')]
    public function ajouter_entree(Request $request,ArticleRepository $art,EntityManagerInterface $entityManager): Response
    {   $entree = new Entree();
        $response="";
        $form=$this->createForm(EntreeType::class,$entree);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {$prix_achat=$form->get('prix_achat')->getData();
          $prix_vente=$form->get('prix_vente')->getData();
          $quantite=$form->get('qte_a')->getData();
          $id=$form->get('article')->getData();
          $article=$art->find($id);
          $article->setPrixAchat($prix_achat);
          $article->setPrixVente($prix_vente);
          $article->setQte($article->getQte()+$quantite);
          $entityManager->persist($article);
          $entityManager->persist($entree);
          $entityManager->flush();
          $response="Stock mise à jour";
        }
        return $this->render('entree/ajouter.entree.html.twig', ['form'=>$form->createView(),'response'=>$response]);
    }
    #[Route('/employe/lister/entrees',name : 'lister_entree')]
  public function lister_entree(EntreeRepository $entreeRepository,Request $request):Response {
        $filtres = $request->get('four');
            $entrees=$entreeRepository->findbyfiltre($filtres);


        $fournisseurs=[];
        $nom_four=$entreeRepository->findAll();
        foreach($nom_four as $e){
            if(!in_array($e->getNomFournisseur(),$fournisseurs))
            {array_push($fournisseurs,$e->getNomFournisseur());
            }
            dump($fournisseurs);
        }
        if($request->get('ajax'))
        {
            return new JsonResponse([
                'content'=> $this->renderView('entree/content.html.twig',['entrees'=>$entrees]),

            ]);
        }
        return $this->render('entree/liste.entree.html.twig',['entrees'=>$entrees,'fournisseurs'=>$fournisseurs]);
    }
    #[Route('/employe/supprimer/entree/{id}',name : 'delete_entree')]
  public function delete_entree(EntityManagerInterface $entityManager,Entree $entree) : Response
    {   $article = $entree->getArticle();
        $article->setQte($article->getQte()-$entree->getQteA());
        $entityManager->remove($entree);
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->redirectToRoute('lister_entree');
    }
    #[Route('/employe/modifier/entree/{id}',name : 'modifier_entree')]
  public function modifier_entree(Entree $entree,Request $request,ArticleRepository $art,EntityManagerInterface $entityManager) : Response {
        $form=$this->createForm(EntreeType::class,$entree);
        $response="";
        $qte_entree_avant=$entree->getQteA();
        $article_avant=$art->find($entree->getArticle());
        $i=$article_avant->getId();
        $id_avant=$art->find($i);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {  $id=$form->get('article')->getData();
           $qa=$form->get('qte_a')->getData();
           $pv=$form->get('prix_vente')->getData();
           $pa=$form->get('prix_achat')->getData();
           $qte=$article_avant->getQte() - $qte_entree_avant ;
            if($id == $id_avant)
            {  $article_avant->setQte($qte + $qa );
               $article_avant->setPrixVente($pv);
               $article_avant->setPrixAchat($pa);
               $entityManager->persist($article_avant);
               $entityManager->persist($entree);
               $entityManager->flush();
            }
            else
            { $article=$art->find($id);
               $article_avant->setQte($qte);
               $article->setQte($qa+$article->getQte());
               $article->setPrixVente($pv);
               $article->setPrixAchat($pa);
               $entityManager->persist($article_avant);
               $entityManager->persist($article);
               $entityManager->persist($entree);
               $entityManager->flush();


            }
        }
        return $this->render('entree/modifier.entree.html.twig',['form'=>$form->createView(),"response"=>$response]);
    }


}
