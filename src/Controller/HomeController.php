<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\AvoirRepository;
use App\Repository\EntreeRepository;
use App\Repository\UserRepository;
use App\Repository\VenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/employe/accueil', name: 'home_page')]
    public function home(ArticleRepository $articleRepository,UserRepository $employe,VenteRepository $venteRepository , AvoirRepository $avoirRepository , EntreeRepository $entreeRepository): Response
    { $date=new \DateTime('today');
        $ventees=$venteRepository->findAll();
        $employes=$employe->findAll();
        $entree_argent=0;
        $sortie_argent=0;
        $vente=$venteRepository->findBy(['date'=>$date]);
        $avoir=$avoirRepository->findBy(['date'=>$date]);
        $entree=$entreeRepository->findBy(['date'=>$date]);
        $ventes=count($vente);
        $avoirs=count($avoir);
        $entrees=count($entree);
        $ventes_rendu=count($venteRepository->findBy(['avoir'=>true]));
        $articles=count($articleRepository->findAll());
        $articles_en_rupture=count($articleRepository->findBy(['qte'=>0]));
        foreach($venteRepository->findAll() as $v){
            $entree_argent=$entree_argent+$v->getTotal();
        }
        foreach($entreeRepository->findAll() as $e)
        {
            $sortie_argent=$sortie_argent+$e->getPrixAchat()*$e->getQteA();
        }
         $caisse=$entree_argent-$sortie_argent;

        return $this->render('accueil/home.page.html.twig', ['ventes'=>$ventes,
            'avoirs'=>$avoirs,
            'entrees'=>$entrees,
            'ventees'=>count($ventees),
            'employes'=>count($employes),
            'caisse'=>$caisse,
            'ventes_rendu'=>$ventes_rendu,
            'articles'=>$articles,
            'articles_en_rupture'=>$articles_en_rupture]);
    }
}
