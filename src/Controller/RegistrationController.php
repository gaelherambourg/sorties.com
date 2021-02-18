<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use claviska\SimpleImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler,
                             AppAuthenticator $authenticator,
                             //Route définie dans config/services.yaml
                             string $uploadDir
                            ): Response
    {
        //Création d'une instance vide liée au formulaire d'inscription
        $participant = new Participant();

        /*
        * On hydrate ici les propriétés qui ne sont pas proposées dans les champs
        * d'enregistrement d'un nouvel utilisateur.
        */
        $participant->setActif(true);
        $participant->setAdministrateur(false);

        //Création du formulaire
        $form = $this->createForm(RegistrationFormType::class, $participant);
        $form->handleRequest($request);

        //Après submit et validation, hash du mot de passe
        if ($form->isSubmitted() && $form->isValid()) {
            // Hashe le mot de passe proposé par l'utilisateur
            $participant->setMotPasse(
                $passwordEncoder->encodePassword(
                    $participant,
                    $form->get('plainPassword')->getData()
                )
            );

            //On assigne aux users le rôle ROLE_USER par défaut
            $participant->setRoles(array("ROLE_USER"));

            //Récupération de la photo de profil, si elle a été soumise
            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            /*
             * S'il y a photo, on hashe le nom du dossier uploadé,
             * pour éviter les attaques. GuessExtension inspecte le
             * fichier et déduit l'extension
             */
            if($photo) {
                $nouveauNomPhoto = md5(uniqid()) . "." . $photo->guessExtension();
                //déplace le fichier dans public/img
                $photo->move($uploadDir, $nouveauNomPhoto);
                //remplit la propriété nomPhoto de l'objet Participant
                $participant->setNomPhoto($nouveauNomPhoto);

                /*
                 * On utilise la librairie de manipulation d'image Claviska :
                 * https://github.com/claviska/SimpleImage
                 */
                $img = new SimpleImage();
                //retrouve l'image à redimensionner
                $img->fromFile($uploadDir . $nouveauNomPhoto)
                    //la redimensionne au plus grand dans un carrée 200 X 200
                    ->bestFit(200, 200)
                    //et la sauvegarde dans un répertoire de public/img
                    ->toFile($uploadDir . "small/" .$nouveauNomPhoto );

                $icon = new SimpleImage();
                //retrouve l'image à transformer en miniature
                $icon->fromFile($uploadDir . $nouveauNomPhoto)
                    //la redimensionne au plus grand dans une miniature de 32x32
                    ->bestFit(32, 32)
                    //et la sauvegarde dans un répertoire de public/img
                    ->toFile($uploadDir . "icon/" .$nouveauNomPhoto );


            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($participant);
            $entityManager->flush();


            return $guardHandler->authenticateUserAndHandleSuccess(
                $participant,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
