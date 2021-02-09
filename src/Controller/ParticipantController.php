<?php


namespace App\Controller;


use App\Form\FormProfilType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ParticipantController extends AbstractController
{
    /**
     * @Route ("/mon_profil", name="modif_profil")
     */
    public function modifierProfil(Request $request,
                                   UserPasswordEncoderInterface $passwordEncoder,
                                   EntityManagerInterface $entityManager)
    {
        //Récupérer les données de l'utilisateur connecté
        $participant = $this->getUser();

        //Créer le formulaire en lui donnant l'objet Participant de l'utilisateur connecté
        $form = $this->createForm(FormProfilType::class, $participant);

        //récupère les données et modifie les données du Participant s'il soumet le formulaire
        $form->handleRequest($request);

        /*
         * Si le formulaire est soumis et valide, ET si l'utilisateur a entré
         * un nouveau mot de passe, ce dernier est hashé
         */
        if ($form->isSubmitted() && $form->isValid())
        {
            if(!$form->get('plainPassword')->getData())
            {dump($form);}
        }



        return $this->render('profil/modifierProfil.html.twig', [
            "profil_form" => $form->createView()
            ]);
    }


}