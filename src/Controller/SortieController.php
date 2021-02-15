<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\FormAnnulationSortieType;
use App\Form\FormLieuType;
use App\Form\FormSortieType;
use App\Repository\EtatRepository;
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

    /**
     * @Route("/sortie/afficher/{id}", name="sortie_afficher")
     */
    public function afficherSortie(Request $request,
                                   EntityManagerInterface $entityManager,
                                   LieuRepository $lieuRepository,
                                   SortieRepository $sortieRepository){

        $idSortie = $request->get('id');
        dump($idSortie);
        $sortie = new Sortie();

        //Récupérer sortie à modifier avec le lieu et la ville associé
        $sortie = $sortieRepository->rechercherSortieParIdAvecLieuEtVilleAssocie($idSortie);

        dump($sortie);

        return $this->render('sortie/afficherSortie.html.twig', [
            "sortie"=> $sortie,
        ]);

    }


    /**
     * @Route("/sortie/annulation/{id}", name="sortie_annulation")
     */
    public function annulerSortie(EntityManagerInterface $entityManager,EtatRepository $etatRepository,Request $request):Response
    {
        //pour test
        $id=25;
        //a decommenter
        //$id = $request->get('id');
        //on récupère l'entité sortie à annuler
        $sortie = new Sortie;
        $sortie2 = new Sortie;
        $sortie = $entityManager->find(Sortie::class,$id);

        //on stocke la description dans une variable
        $description_origine = $sortie->getDescriptioninfos();
        dump($description_origine);

        //on recupere le motif d'annulation
        $form = $this->createForm(FormAnnulationSortieType::class, $sortie2);
        $form->handleRequest($request);

        $description_motif=$sortie2->getDescriptioninfos();
        dump($description_motif);

        //on associe les deux descriptions
        $description_finale = $description_origine."\n\nMotif de l'annulation :\n".$description_motif;
        dump($description_finale);

        if($form -> isSubmitted()){
            //on hydrate l'entité avec la nouvelle description et on change l'etat->6 pour annulée
            $sortie->setDescriptioninfos($description_finale);
            $etat = new Etat();
            $etat = $etatRepository->find(6);
            $sortie->setEtatsNoEtat($etat);

            $entityManager->persist($sortie);
            $entityManager->flush();

            //redirection vers la page d'accueil
            return $this->redirectToRoute('AccueilSorties');
        }


        return $this->render('sortie/annulerSortie.html.twig',[
            "sortie"=> $sortie,
            "annulation_form"=>$form->createView()
        ]);
    }


}