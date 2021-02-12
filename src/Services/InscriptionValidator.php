<?php


namespace App\Services;


use Symfony\Component\Validator\Context\ExecutionContextInterface;

class InscriptionValidator
{



    public function validationInscription($date)
    {
        $message="";
        if($date< new \DateTime()){
            $message='Les inscriptions sont closes';
        }
        return $message;
    }


}