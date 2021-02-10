<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/lieu/ajout/{idVille]", name="ajout_lieu")
     */
    public function ajoutLieu(Request $request,
                              EntityManagerInterface $entityManager): Response
    {
        $request->get('form_sortie');

        return $this->render('lieu/ajoutLieu.html.twig', [
            'controller_name' => 'LieuController',
        ]);
    }
}
