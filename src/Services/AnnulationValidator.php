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



    }