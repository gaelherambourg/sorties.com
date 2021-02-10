<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Sortie;
use App\Form\ListeSortiesType;
use App\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function listSorties(Request $request):Response
    {
       $sortiesRepo = $this->getDoctrine()->getRepository(Sortie::class) ;

       //$sorties = $sortiesRepo->findAll();

        //intialisation des données
        $data = new SearchData();
        //creation du formulaire avec en parametre $data->modification de l'objet $data representant les données
        //lors d'un handleRequest
        $form= $this->createForm(SearchFormType::class,$data);

        //recupération des données
        $form->handleRequest($request);

        dump($data);

        $id = $this->getUser()->getId();
        dump($id);

        $date = (new \DateTime('now'));
        dump($date);

        //requete. On peut passer en parametre les filtres reçus du form + id user + date actuelle
        $sorties = $sortiesRepo->trouverToutesSorties($data,$id, $date);
        dump($sorties);

        //création d'une instance de la classe form
        //$form =$this->createForm(ListeSortiesType::class);
        //recuperation des données soumises dans la requête
       // $form ->handleRequest($request);
        //données stockées si le formulaire a été soumis
       // $data = $form->getData();

       // dump($data);


        //on verifie la validation du form
        //if($form->isSubmitted()&& $form->isValid())
       // {
        //  $sorties = $sortiesRepo->filterSorties($data["campus"],$data["nom"],$data["datedebut"],$data["datecloture"],
          //               $data["organisateur"], $data["inscrit"],$data["pasInscrit"],$data["passees"]);

       // }


        $participe =false;

        return $this ->render('main/listeSorties.html.twig', [
            "sorties" => $sorties,
            "participe" =>$participe,
            "selection_form" => $form->createView()
        ]);



    }

}
