<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeController extends AbstractController
{
    #[Route('/admin/lister/employes', name: 'employees_list')]
    public function list_emp(UserRepository $user): Response
    {       $users = $user->findAll();
            return $this->render('employe/liste.employe.html.twig', ['users' => $users]);

    }

}
