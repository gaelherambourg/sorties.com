<?php


namespace App\Form;


use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FormProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', null, ['label' => 'Pseudo :'])
            ->add('nom', null, ['label'=>'Nom :'])
            ->add('prenom', null, ['label'=>'Prénom :'])
            ->add('campusNoCampus', EntityType::class, [
                'class'=> Campus::class,
                'choice_label'=> 'nom',
                'label'=>'Campus : '
            ])
            ->add('telephone', null, ['label'=>'Numéro de téléphone (optionnel) :'])
            ->add('mail', EmailType::class, ['label'=>'Adresse email :'])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type'=> PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Mot de passe de confirmation différent.',
                'first_options' => array('label' => ' ', 'attr' =>
                    ['placeholder' => 'Nouveau mot de passe (6 caractères min)']),
                'second_options' => array('label' => ' ',
                    'attr' => ['placeholder' => 'Confirmer le nouveau mot de passe.']),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ 6 }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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