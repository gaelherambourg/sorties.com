<?php


namespace App\Services;


class PublicationValidator
{

    public function validationPublication($idOrg, $idUser)
    {
        $message=null;
        if($idUser!=$idOrg){
            $message="Vous n'avez pas les droits pour publier cette sortie";
        }
        return $message;


    }


}