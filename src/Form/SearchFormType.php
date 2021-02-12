<?php


namespace App\Form;


use App\Data\SearchData;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class SearchFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('campus',EntityType::class, [
                'class'=> Campus::class,
                'choice_label'=> 'nom',
                'label'=>'Campus : '
            ])*/

            ->add('campus', ChoiceType::class, [
                'choices'=>
                    [
                        'Tous' =>0,
                        'Rennes'=>1,
                        'Nantes'=>2,
                        'Quimper'=>3,
                        'Niort'=>4

                    ]
            ])
            ->add('recherche', SearchType::class,[
                'required'=>false,
                'label' => 'Le nom de la sortie contient :',
                'attr' =>[
                    'placeholder'=>'search'
                ]
            ])
            ->add('datedeb',DateTimeType::class,[
                'required'=>false,
                "date_widget"=>"single_text",
                "time_widget"=>"single_text",
                'label' => 'Entre :'
            ])
            ->add('datefin',DateTimeType::class,[
                'required'=>false,
                "date_widget"=>"single_text",
                "time_widget"=>"single_text",
                'label' => 'Et :'
            ])
            ->add('organisateur', CheckboxType::class, [
                'required'=>false,
                'label' => "Sorties dont je suis l'organisateur/trice :"
            ])
            ->add('inscrit', CheckboxType::class, [
                'required'=>false,
                'label' => "Sorties auxquelles je suis inscrit/e:"
            ])
            ->add('pasInscrit', CheckboxType::class, [
                'required'=>false,
                'label' => "Sorties auxquelles je ne suis pas inscrit/e :"
            ])
            ->add('passees', CheckboxType::class, [
                'required'=>false,
                'label' => "Sorties passées :"
            ])
            ->add('submit', SubmitType::class,  ["label"=>"Rechercher"])

            ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver ->setDefaults([
            //class servant à représenter els données
            'data_class' => SearchData::class,
            //methode Get par defaut->parametres passent par l'url
            'method' => 'GET',
            //on desactive la protection csrf car form de recherche -> pas de pb
            'csrf_protection' =>false
        ]);
    }


    /**
    * permet d'avoir une url plus claire
    */
    public function getBlockPrefix()
    {
        return '';
    }





}