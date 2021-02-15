<?php


namespace App\Services;


use Symfony\Component\Validator\Context\ExecutionContextInterface;

class InscriptionValidator
{



    public function validationInscriptionDate($date)
    {
        $message=null;
        if($date< new \DateTime()){
            $message='Les inscriptions pour cette sortie sont closes';
        }
        return $message;
    }

    public function validationInscriptionEtat($etat)
    {
        $message=null;
        if($etat != 2){
            $message='Les inscriptions à cette sortie ne sont pas ouvertes';
        }
        return $message;
    }

    public function validationInscriptionPlace($nbPlaces, $nbInscrits)
    {
        $message=null;
        if($nbInscrits>=$nbPlaces){
            $message="Il n'y a plus de place disponible pour cette sortie";
        }
        return $message;
    }

    public function validationInscriptionDoublon($idUser, $participants)
    {
        dump($participants);
        $message=null;
        foreach($participants as $participant){
            if($participant->getId() ==$idUser){
                $message="Vous êtes déjà inscrit à cette sortie";
            }
        }
        return $message;
    }


    public function validationDesistement($idEtatSortie)
    {
        $message=null;
        if($idEtatSortie == 4){
            $message= 'Vous ne pouvez pas vous désister. La sortie est en cours';
        }
        return $message;
    }


}