<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                "label"=>"Nom de la sortie :"
            ])
            ->add('datedebut', DateTimeType::class, [
                "label"=>"Date de début :",
                "date_widget"=>"single_text",
                "time_widget"=>"single_text"
            ])
            ->add('datecloture', DateTimeType::class, [
                "label"=>"Date de clôture",
                "date_widget"=>"single_text",
                "time_widget"=>"single_text"
            ])
            ->add('nbinscriptionsmax', IntegerType::class, [
                "label"=>"Nombre de places :"
            ])
            ->add('duree', IntegerType::class, [
                "label"=>"Durée :"
            ])
            ->add('descriptioninfos', TextareaType::class, [
                "label"=>"Descriptions et infos :"
            ])
//            ->add('Campus', EntityType::class, [
//                "label"=>"Campus :",
//                "class"=>Campus::class,
//                "choice_label"=>'nom',
//                "mapped"=>false
//            ])
            ->add('Ville', EntityType::class, [
                "label"=>"Ville :",
                "class"=>Ville::class,
                "choice_label"=>'nom',
                "mapped"=>false
            ])
            ->add('lieux_no_lieu', EntityType::class, [
                "label"=>"Lieu :",
                "class"=>Lieu::class,
                "choice_label"=>"nom"
            ])
            ->add('Rue', TextType::class, [
                "label"=> "Rue :",
                "mapped"=>false
            ])
            ->add('Latitude', TextType::class, [
                "label"=> "Latitude :",
                "mapped"=>false
            ])
            ->add('Longitude', TextType::class, [
                "label"=> "Longitude :",
                "mapped"=>false
            ])
            ->add('Enregistrer', SubmitType::class, [
                "attr"=>["value"=>"Enregistrer"]
            ])
            ->add('Publier', SubmitType::class, [
                "attr"=>["value"=>"Publier"]
            ])
//            ->add('Annuler', SubmitType::class, [
//                "attr"=>["value"=>"Annuler"],
//            ])
            ->add('Supprimer', SubmitType::class, [
                "attr"=>["value"=>"Supprimer"]
            ])
//            ->add('etatsortie')
//            ->add('urlPhoto')
//            ->add('participants')
//            ->add('organisateur')
//            ->add('etats_no_etat', EntityType::class, [
//                'class'=>Etat::class,
//                'choice_label'=>'libelle'
//            ])
//            ->add('lieux_no_lieu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
