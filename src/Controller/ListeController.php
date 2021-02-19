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
        return $this->render('liste/ajoutLieu.html.twig', [
            'controller_name' => 'ListeController',
        ]);
    }*/



    /**
     * affiche la page avec l'ensemble des sorties
     * @Route("/", name="AccueilSorties")
     */
    public function listSorties(Request $request):Response
    {
       $sortiesRepo = $this->getDoctrine()->getRepository(Sortie::class) ;

       //$sorties = $sortiesRepo->findAll();

        $tab2 = $request->get('tab');
        dump($tab2);
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

        //requete2
        $tabNbInscrits = array();
        $sorties2 = $sortiesRepo->participantsSortie();
        //$tabId = array();
        foreach($sorties2 as $sortie){
            $idSortie= $sortie->getId();
            // $tabId = $sortie->getId();
            $participants = $sortie->getParticipants();
            $nbInscrits = count($participants);
            //dump($nbInscrits);
            $tabNbInscrits[$idSortie]=$nbInscrits;

        }


        //requete. On peut passer en parametre les filtres reçus du form + id user + date actuelle
        $sorties = $sortiesRepo->trouverToutesSorties($data,$id, $date);
        dump($sorties);



        $participe =false;

        return $this ->render('main/listeSorties.html.twig', [
            "sorties" => $sorties,
            //"tabNbInscrits"=>$tabNbInscrits,
            "participe" =>$participe,
            "tabErreurs"=>$tab2,
            "selection_form" => $form->createView()
        ]);



    }

}
