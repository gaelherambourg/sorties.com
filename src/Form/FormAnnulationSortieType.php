<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormAnnulationSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           // ->add('nom')
            //->add('datedebut')
            //->add('duree')
           // ->add('datecloture')
           // ->add('nbinscriptionsmax')
            ->add('descriptioninfos',TextareaType::class,[
               'label' => 'Motif :'
           ])
            ->add('submit', SubmitType::class,  ["label"=>"Enregistrer"])
           // ->add('etatsortie')
           // ->add('urlPhoto')
           // ->add('participants')
           // ->add('organisateur')
           // ->add('etats_no_etat')
           // ->add('lieux_no_lieu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
