<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\ListeSortiesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListeController extends AbstractController
{
    /**
     * @Route("/liste", name="liste")
     */
   /* public function index(): Response
    {
        return $this->render('liste/index.html.twig', [
            'controller_name' => 'ListeController',
        ]);
    }*/


    /**
     * affiche la page avec l'ensemble des sorties
     * @Route("/liste", name="listeSorties")
     */
    public function listSorties()
    {
       $sortiesRepo = $this->getDoctrine()->getRepository(Sortie::class) ;

       /*$sorties = $sortiesRepo->findAll();

       return $this ->render('main/listeSorties.html.twig', [
            "sorties" => $sorties
           ]);*/




        //requete numero 2
        $sorties = $sortiesRepo->trouverToutesSorties();
        dump($sorties);

        //création d'une instance de la classe form
        $form =$this->createForm(ListeSortiesType::class);

        $participe =false;

        return $this ->render('main/listeSorties.html.twig', [
            "sorties" => $sorties,
            "participe" =>$participe,
            "selection_form" => $form->createView()
        ]);



    }

}
