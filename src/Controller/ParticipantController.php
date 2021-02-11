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
         * un nouveau mot de passe, ce dernier est hashé. Sinon, le mdp
         * demeurera le même.
         */
        if ($form->isSubmitted() && $form->isValid())
        {
            if(!empty($form->get('plainPassword')->getData()))
            {$participant->setMotPasse(
                $passwordEncoder->encodePassword(
                    $participant,
                    $form->get('plainPassword')->getData()
                )
            );
            }

            /*
             * Comme il s'agit simplement d'éventuelles modifications, pas besoin de persist().
             * Le flush() suffira à enregistra en base les champs modifiés.
             */
            $entityManager->flush();


            /*
             * En cas de validation, l'utilisateur est redirigé vers la page d'accueil.
             * Un message (via addFLash() ) l'y informera de l'enregistrement des nouvelles données.
             */
            $this->addFlash('success', 'Vos coordonnées ont bien été modifiées.');
            return $this->redirectToRoute('AccueilSorties');

        }



        return $this->render('profil/modifierProfil.html.twig', [
            "profil_form" => $form->createView()
            ]);
    }


}