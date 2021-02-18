<?php


namespace App\Controller;


use App\Form\FormProfilType;
use claviska\SimpleImage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ParticipantController extends AbstractController
{
    /**
     * @Route ("/mon_profil", name="modif_profil")
     */
    public function modifierProfil(Request $request,
                                   UserPasswordEncoderInterface $passwordEncoder,
                                   EntityManagerInterface $entityManager,
                                   //Route définie dans config/services.yaml
                                   string $uploadDir): Response
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
            $participant->setMotPasse(
                $passwordEncoder->encodePassword(
                    $participant,
                    $form->get('plainPassword')->getData()
                )
            );


            //Récupération de la photo de profil, si elle a été soumise
            /** @var UploadedFile $photoN */
            $photoN = $form->get('photo')->getData();
            dump($photoN);
            /*
             * S'il y a photo, on hashe le nom du dossier uploadé,
             * pour éviter les attaques. GuessExtension inspecte le
             * fichier et déduit l'extension
             */
            if($photoN) {
                $nouveauNomPhoto = md5(uniqid()) . "." . $photoN->guessExtension();
                dump($nouveauNomPhoto);
                //déplace le fichier dans public/img
                $photoN->move($uploadDir, $nouveauNomPhoto);
                //remplit ou remplace la propriété nomPhoto de l'objet Participant
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
                    ->bestFit(40, 40)
                    //et la sauvegarde dans un répertoire de public/img
                    ->toFile($uploadDir . "icon/" .$nouveauNomPhoto );
            }




            /*
             * Comme il s'agit simplement d'éventuelles modifications, pas besoin de persist().
             * Le flush() suffira à enregistrer en base les champs modifiés.
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