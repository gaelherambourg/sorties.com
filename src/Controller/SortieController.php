<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\FormLieuType;
use App\Form\FormSortieType;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/creation", name="sortie_creation")
     */
    public function creationSortie(Request $request,
                                   EntityManagerInterface $entityManager, LieuRepository $lieuRepository): Response
    {
        //Création d'une instance de notre entité, qui sera eventuellement sauvegarder en base de données
        $sortie = new Sortie();

        //retourne l'entité user de l'utilisateur connecté
        //$user = $this->getUser();
        $lieu2 = new Lieu();
        $form2 = $this->createForm(FormLieuType::class, $lieu2);
        $hasLieu = false;
        $lieux = $lieuRepository->findAll();
        //dump($lieux);
        if(!empty($lieux))
        {
            $sortie->setLieuxNoLieu($lieux[0]);
            $hasLieu = true;
        }

        //Créer une instance du form, en lui associant notre entité
        $form = $this->createForm(FormSortieType::class, $sortie);
        //->add('author',null,['attr'=>['value'=>$this->getUser()->getPseudo()]]); //Pré remplir le champs Author avec le pseudo de l'utilisateur connecté

        //prends les données du formulaire et les hydrates dans mon entité
        $form->handleRequest($request);

        $orga1 = $entityManager->find(Participant::class,$this->getUser()->getId());

        //est ce que le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()){


            //hydrater les propriétés manquantes
            $sortie->setOrganisateur($orga1);
            //Si le formulaire a été soumis avec le bouton enregistrer, on met l'état créée pour la sortie
            if($form->get('Enregistrer')->isClicked())
            {
                $sortie->setEtatsNoEtat($entityManager->find(Etat::class,1));
            }
            //Si le formulaire a été soumis avec le bouton Publier, on passe l'état de la sortie à Ouverte
            elseif($form->get('Publier')->isClicked())
            {
                $sortie->setEtatsNoEtat($entityManager->find(Etat::class,2));
            }
            //Si le formulaire a été soumis avec le bouton Annuler, on retourne vers la page d'accueil
            else
            {
                return $this->redirectToRoute('main_home');
            }

            //déclenche l'insert en bdd
            $entityManager->persist($sortie);
            $entityManager->flush();

            //Créer un message en session
            $this->addFlash('success', 'La sortie a bien été ajouté !');

            //Créer une redirection vers une autre page
            return $this->redirectToRoute('AccueilSorties');
        }


        return $this->render('sortie/creationSortie.html.twig', [
            "sortie_form"=> $form->createView(),
            "sortie"=> $sortie,
            "lieuTrouve"=> $hasLieu,
            "lieu_form"=> $form2->createView()
        ]);
    }



    /**
     * @Route("/sortie/modifier/{id}", name="sortie_modif")
     */
    public function modifierSortie(Request $request,
                                   EntityManagerInterface $entityManager,
                                   LieuRepository $lieuRepository,
                                   SortieRepository $sortieRepository): Response
    {
        $idSortie = $request->get('id');

        //Récupérer sortie à modifier
        $sortie = $entityManager->find(Sortie::class, $idSortie);

        //Récupérer l'entité lieu associé à la sortie
        $lieu = $lieuRepository->find($sortie->getLieuxNoLieu()->getId());

        //Hydrater la sortie du lieu correspondant
        $sortie->setLieuxNoLieu($lieu);

        //Créer une instance du form, en lui associant notre entité
        $form_sortie_modif = $this->createForm(FormSortieType::class, $sortie);


        $lieu2 = new Lieu();
        //Créer une instance du form d'ajout de lieu
        $form2 = $this->createForm(FormLieuType::class, $lieu2);

        //prends les données du formulaire et les hydrates dans mon entité
        $form_sortie_modif->handleRequest($request);

        //est ce que le formulaire est soumis et valide
        if($form_sortie_modif->isSubmitted() && $form_sortie_modif->isValid()){

            //Si le formulaire a été soumis avec le bouton enregistrer, on met l'état créée pour la sortie
            if($form_sortie_modif->get('Enregistrer')->isClicked())
            {
                $sortie->setEtatsNoEtat($entityManager->find(Etat::class,1));
            }
            //Si le formulaire a été soumis avec le bouton Publier, on passe l'état de la sortie à Ouverte
            elseif($form_sortie_modif->get('Publier')->isClicked())
            {
                $sortie->setEtatsNoEtat($entityManager->find(Etat::class,2));
            }
            elseif($form_sortie_modif->get('Supprimer')->isClicked())
            {
                $entityManager->remove($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'La sortie a bien été supprimé !');
                return $this->redirectToRoute('AccueilSorties');
            }
            //Si le formulaire a été soumis avec le bouton Annuler, on retourne vers la page d'accueil
            else
            {
                return $this->redirectToRoute('AccueilSorties');
            }

            //déclenche l'update en bdd
            $entityManager->persist($sortie);
            $entityManager->flush();

            //Créer un message en session
            $this->addFlash('success', 'La sortie a bien été modifié !');

            //Créer une redirection vers une autre page
            return $this->redirectToRoute('AccueilSorties');
        }


        return $this->render('sortie/modifSortie.html.twig', [
            "sortie_form"=> $form_sortie_modif->createView(),
            "sortie"=> $sortie,
            "lieu_form"=> $form2->createView()
        ]);
    }
}