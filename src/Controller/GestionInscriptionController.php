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

        $sortie = $sortieRepository->find($id);
        $participant = $participantRepository->find($idUser);

        //creation d'un tableau de messages
        $tabErreurs = array();
        //appel au service inscriptionValdator
        $tabErreurs[]=$inscriptionValidator->validationInscription($sortie->getDatecloture());
        $tabErreurs[]="oups";
        dump($tabErreurs);
        dump($idUser);
        var_dump($idUser);



            $sortie->addParticipant($participant);

            //appel a entitymanager pour la sauvegarder ds la bdd
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sortie);
            $entityManager->flush();

            //ajout d'un message de confirmation
            $this->addFlash('succes', 'Votre inscription a bien été enregistrée');

            return $this->redirectToRoute('AccueilSorties');


    }

    /**
     * @Route("/desistement/{id}", name="desistement_sortie", requirements={"id": "\d+"})
     */
    public function SeDesisterSortie(SortieRepository $sortieRepository,ParticipantRepository $participantRepository,$id)
    {
        //récupération de l'utilisateur connecté
        $idUser = $this->getUser()->getId();

        var_dump($idUser);

        $sortie = $sortieRepository->find($id);
        $participant = $participantRepository->find($idUser);

        $sortie->removeParticipant($participant);

        //appel a entitymanager pour la sauvegarder ds la bdd
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        //ajout d'un message de confirmation
        $this->addFlash('succes', 'Votre désistement a bien été enregistré');

        return $this->redirectToRoute('AccueilSorties');

    }

}
