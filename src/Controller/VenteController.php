<?php

namespace App\Controller;

use App\Entity\Vente;
use App\Form\VenteType;

use App\Repository\ArticleRepository;
use App\Repository\VenteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VenteController extends AbstractController
{
    #[Route('/employe/ajouter/vente', name: 'add_sale')]
    public function index(EntityManagerInterface $entityManager , Request $request,ArticleRepository $art): Response
    { $vente = new Vente();
        $response="";
        $success="";
        $form=$this->createForm(VenteType::class,$vente);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { $qte_c = $form->get('qte_c')->getData();
          $id=$form->get('article')->getData();
          $article2=$art->find($id);
          $qte=$article2->getQte();
          if($qte >= $qte_c){
              $article2->setQte($article2->getQte() - $qte_c);
              $vente->setTotal($qte_c*$article2->getPrixVente());
              $vente->setPrixVente($article2->getPrixVente());
              $entityManager->persist($article2);
              $entityManager->persist($vente);
              $entityManager->flush();
              $success="Vente confirmée";
          }
          else {
              $response="La quantité voulue est indisponible";
          }

        }
        return $this->render('vente/ajouter.vente.html.twig',['form'=>$form->createView(),'response'=>$response,'success'=>$success]);
    }

    #[Route('/employe/lister/ventes',name : 'vente_list')]
    public function lister_vente(VenteRepository $vente):Response {
        $ventes=$vente->findAll();
        return $this->render('vente/liste.vente.html.twig',['ventes'=>$ventes]);
    }
    #[Route('/employe/supprimer/vente/{id}',name : 'delete_vente')]
    public function supprimer_vente(Vente $vente ,EntityManagerInterface $entityManager) : Response {
        $article=$vente->getArticle();
        $qte=$vente->getQteC();
        $article->setQte($article->getQte() + $qte);
        $entityManager->persist($article);
        $entityManager->remove($vente);
        $entityManager->flush();
        return $this->redirectToRoute('vente_list');
    }
    #[Route('/employe/modifier/vente/{id}',name : 'modifier_vente')]
public function modifier_vente(Vente $vente , EntityManagerInterface $entityManager,Request $request,VenteRepository $v , ArticleRepository $art) : Response {
        $form =$this->createForm(VenteType::class , $vente);
        $qte_avant=$vente->getQteC();
        $article_avant=$vente->getArticle();
        $i=$article_avant->getId();
        $id_avant=$art->find($i);
        $form->handleRequest($request);
        $success="";
        $response="";
        if($form->isSubmitted() && $form->isValid())
        {   $qte_c = $form->get('qte_c')->getData();
            $id=$form->get('article')->getData();
            $qte=$article_avant->getQte()+$qte_avant;
            //le cas où on modifier la vente du meme article
            if($id == $id_avant){
            if($qte >= $qte_c) {
                    $article_avant->setQte($qte - $qte_c);
                    $vente->setTotal($qte_c*$article_avant->getPrixVente());
                    $vente->setPrixVente($article_avant->getPrixVente());
                    $entityManager->persist($vente);
                    $entityManager->persist($article_avant);
                    $entityManager->flush();
                    $success="Mise à jour avec succès";}
                else {
                    $response="La quantité voulue est indisponible";
                }

            }
            else {
                $article2 = $art->find($id);
                if ($article2->getQte() >= $qte_c) {
                    $article_avant->setQte($qte);
                    $vente->setTotal($qte_c * $article2->getPrixVente());
                    $article2->setQte($article2->getQte() - $qte_c);
                    $entityManager->persist($article_avant);
                    $entityManager->persist($vente);
                    $entityManager->persist($article2);
                    $entityManager->flush();
                    $success = "Mise à jour avec succès";}
                 else {
                    $response = "La quantité voulue est indisponible";}
            }


        }
        return $this->render('vente/modifier.vente.html.twig',['form'=>$form->createView(),'success'=>$success,'response'=>$response]);
    }
    #[Route('/employe/lister/ventes/rendues',name : 'list_vente_rendu')]
public function list_vente_rendu(VenteRepository $vente) : Response {
        $ventes=$vente->findBy(['avoir'=>true]);
        return $this->render('vente/liste.vente.rendu.html.twig',['ventes'=>$ventes]);
    }

    #[Route('employe/vente/{id}',name : 'imprimer')]
public function imprimer(Vente $vente , Pdf $pdf ) : Response {
        $html = $this->renderView("vente/imprimer.vente.html.twig", [
            'vente'=>$vente,
        ]);
        $pdf->setOption('margin-top', '2cm');
        $filename='vente'.$vente->getId().'.pdf';
        return new PdfResponse($pdf->getOutputFromHtml($html),$filename);
    }

}
