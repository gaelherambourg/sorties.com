<?php


namespace App\Services;


    class AnnulationValidator
    {

       public function annulationSortieOrganisateur($idUser,$idOrg)
       {
           $message=null;
           if($idUser!=$idOrg){
               $message="Vous n'avez pas les droits pour annuler cette sortie";
           }
           return $message;

       }

       public function annulationSortieEnCours($idEtatSortie)
       {
           $message=null;
           if($idEtatSortie == 4){
               $message="Vous ne pouvez pas annuler une sortie en cours";
           }
           return $message;
       }




    }