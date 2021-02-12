<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', null, ['label' => 'Pseudo : '])
            ->add('nom', null, ['label'=>'Nom : '])
            ->add('prenom', null, ['label'=>'Prénom : '])
            ->add('campusNoCampus', EntityType::class, [
                    'class'=> Campus::class,
                    'choice_label'=> 'nom',
                    'label'=>'Campus : '
            ])
            ->add('telephone', null, ['label'=>'Numéro de téléphone (optionnel) : '])
            ->add('mail', EmailType::class, ['label'=>'Adresse email : '])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type'=> PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Mot de passe de confirmation différent.',
                'first_options' => array('label' => 'Mot de passe : '),
                'second_options' => array('label' => 'Confirmation du mot de passe : '),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins 6 caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            /*
             * Ce champ n'est la propriété de l'entité (nom_photo). C'est un champ crée
             * pour contenir le fichier. D'où son mapped à false
             */
            ->add('photo', FileType::class, [
                'mapped' => false,
                'label' => 'Photo de profil : ',
                'constraints' => [
                    //Contraintes de validations spécifiques aux images.
                    new Image([
                        'maxSize' => '2000k',
                        'maxSizeMessage' => 'Le fichier est trop volumineux.'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
