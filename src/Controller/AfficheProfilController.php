<?php


namespace App\Controller;


use App\Repository\ParticipantRepository;
use Symfony\Component\Routing\Annotation\Route;

class AfficheProfilController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @Route ("/profil/{id}", name="affiche_profil", requirements={"id": "\d+"})
     */
    public function afficherProfil(int $id, ParticipantRepository $participantRepository)
    {
        //Chercher dans la BDD le profil dont l'ID est dans l'URL
        $participant = $participantRepository->find($id);

        //Erreur 404 si le profil est introuvable
        if(!$participant){
            throw $this->createNotFoundException('Ce profil ne se trouve plus en base.');
        }

        dump($participant);


        return $this->render("profil/afficherProfil.html.twig", [
            //Passer l'id et le participant récupéré à l'affichage
            "id_participant" => $id,
            "participant" => $participant
        ]);
    }

}