<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListeSortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('recherche', SearchType::class,[
                'mapped'=>false,
                'label' => 'Le nom de la sortie contient :'
            ])
            ->add('datedebut',DateTimeType::class,[
                //'mapped'=>false,
                "date_widget"=>"single_text",
                "time_widget"=>"single_text",
                'label' => 'Entre :'
            ])
            ->add('datecloture',DateTimeType::class,[
                "date_widget"=>"single_text",
                "time_widget"=>"single_text",
                'label' => 'Et :'
            ])
            ->add('organisateur', CheckboxType::class, [
                'mapped'=>false,
                'label' => "Sorties dont je suis l'organisateur/trice :"
            ])
            ->add('inscrit', CheckboxType::class, [
                'mapped'=>false,
                'label' => "Sorties auxquelles je suis inscrit/e:"
            ])
            ->add('pasInscrit', CheckboxType::class, [
                'mapped'=>false,
                'label' => "Sorties auxquelles je ne suis pas inscrit/e :"
            ])
            ->add('passees', CheckboxType::class, [
                'mapped'=>false,
                'label' => "Sorties passées :"
            ])
            ->add('submit', SubmitType::class,  ["label"=>"Rechercher"])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
