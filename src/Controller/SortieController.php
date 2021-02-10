<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\FormSortieType;
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
                                   EntityManagerInterface $entityManager): Response
    {
        //Création d'une instance de notre entité, qui sera eventuellement sauvegarder en base de données
        $sortie = new Sortie();
        //retourne l'entité user de l'utilisateur connecté
        //$user = $this->getUser();

        $lieu = $entityManager->find(Lieu::class,4);
        dump($lieu);

        //Créer une instance du form, en lui associant notre entité
        $form = $this->createForm(FormSortieType::class, $sortie);
        //->add('author',null,['attr'=>['value'=>$this->getUser()->getPseudo()]]); //Pré remplir le champs Author avec le pseudo de l'utilisateur connecté

        //prends les données du formulaire et les hydrates dans mon entité
        $form->handleRequest($request);

        $orga1 = $entityManager->find(Participant::class,2);

//        var_dump($request->get("form_sortie"));
//        var_dump($form);
//        //var_dump($form->getData());
//        var_dump($form->get("Publier")->getData());
//        var_dump($form->get("Enregistrer")->getData());

//        var_dump($form->get('Publier')->isClicked());
//        var_dump($form->get('Enregistrer')->isClicked());

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
                //return $this->redirectToRoute('main_home');
            }

            //déclenche l'insert en bdd
            $entityManager->persist($sortie);
            $entityManager->flush();

            //Créer un message en session
            $this->addFlash('success', 'La sortie a bien été ajouté !');

            //Créer une redirection vers une autre page
            return $this->redirectToRoute('listeSorties');
        }


        return $this->render('sortie/creationSortie.html.twig', [
            "sortie_form"=> $form->createView(),
            "lieu" => $lieu
        ]);
    }
}
