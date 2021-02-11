<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
                             AppAuthenticator $authenticator): Response
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

            //on assigne aux users le rôle ROLE_USER par défaut
            $participant->setRoles(array("ROLE_USER"));

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
