<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Services\InscriptionValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionInscriptionController extends AbstractController
{



    /**
     * @Route("/gestion/{id}", name="inscription_sortie", requirements={"id": "\d+"})
     */
    public function InscriptionSortie(InscriptionValidator $inscriptionValidator,SortieRepository $sortieRepository,ParticipantRepository $participantRepository,$id)
    {

        //récupération de l'utilisateur connecté
        $idUser = $this->getUser()->getId();
        //recuperation du role de l'utilisateur connecté
        $role = $this->getUser()->getRoles();


        $sortie = $sortieRepository->find($id);
        $participant = $participantRepository->find($idUser);

        //recupération du nombre de places
        $nbPlaces = $sortie->getNbinscriptionsmax();
        //récupération du nombre d'inscription
        $nbInscrits = count($sortie->getParticipants());

        //creation d'un tableau de messages
        $tabErreurs = array();

        //appel aux services inscriptionValidator
        $message=$inscriptionValidator->validationInscriptionDate($sortie->getDatecloture());
        $message2 =$inscriptionValidator->validationInscriptionEtat($sortie->getEtatsNoEtat()->getId());
        $message3 = $inscriptionValidator->validationInscriptionPlace($nbPlaces,$nbInscrits);
        $message4 = $inscriptionValidator->validationInscriptionDoublon($idUser, $sortie->getParticipants());

        if($message){
            $tabErreurs[]=$message;
        }
        if($message2){
            $tabErreurs[]=$message2;
        }
        if($message3){
            $tabErreurs[]=$message3;
        }
        if($message4){
            $tabErreurs[]=$message4;
        }

        if(empty($tabErreurs)){

            $sortie->addParticipant($participant);

            //appel a entitymanager pour la sauvegarder ds la bdd
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sortie);
            $entityManager->flush();

            //ajout d'un message de confirmation
            $this->addFlash('success', 'Votre inscription a bien été enregistrée');

            return $this->redirectToRoute('AccueilSorties');
        }
        else{

            foreach ($tabErreurs as $erreur){
                $this->addFlash('error', $erreur);
            }

            return $this->redirectToRoute('AccueilSorties');
        }



    }

    /**
     * @Route("/desistement/{id}", name="desistement_sortie", requirements={"id": "\d+"})
     */
    public function SeDesisterSortie(InscriptionValidator $inscriptionValidator,SortieRepository $sortieRepository,ParticipantRepository $participantRepository,$id)
    {
        //récupération de l'utilisateur connecté
        $idUser = $this->getUser()->getId();

        var_dump($idUser);

        $sortie = $sortieRepository->find($id);
        $participant = $participantRepository->find($idUser);

        //creation d'un tableau de messages
        $tabErreurs = array();
        //appel au service inscriptionValdator
        $message=$inscriptionValidator->validationDesistement($sortie->getEtatsNoEtat()->getId());

        if($message){
            $tabErreurs[]=$message;
        }

        if(empty($tabErreurs)){

            $sortie->removeParticipant($participant);

            //appel a entitymanager pour la sauvegarder ds la bdd
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            //ajout d'un message de confirmation
            $this->addFlash('success', 'Votre désistement a bien été enregistré');

            return $this->redirectToRoute('AccueilSorties');
        }
        else{

            foreach ($tabErreurs as $erreur){
                $this->addFlash('error', $erreur);
            }

            return $this->redirectToRoute('AccueilSorties');
        }



    }

}
