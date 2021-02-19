<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleFormType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class VilleController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/ville", name="liste_ville")
     */
    public function listerVille(Request $request,
                                EntityManagerInterface $entityManager,
                                VilleRepository $villeRepository): Response
    {
        $ville = new Ville();

        $villes = $villeRepository->findAll();
        dump($villes);

        //Créer une instance du form, en lui associant notre entité
        $form_ville = $this->createForm(VilleFormType::class, $ville);


        //prends les données du formulaire et les hydrates dans mon entité
        $form_ville->handleRequest($request);

        //est ce que le formulaire est soumis et valide
        if($form_ville->isSubmitted() && $form_ville->isValid()){


            //déclenche l'insert en bdd
            $entityManager->persist($ville);
            $entityManager->flush();

            //Créer un message en session
            $this->addFlash('success', 'La ville a bien été ajouté !');

            //Créer une redirection vers une autre page
            return $this->redirectToRoute('liste_ville');
        }

        return $this->render('ville/listerVille.html.twig', [
            'ville_form'=> $form_ville->createView(),
            'villes' => $villes,
        ]);
    }

//    /**
//     * @IsGranted("ROLE_ADMIN")
//     * @Route("/admin/suppr_ville", name="suppr_ville")
//     */
//    public function SupprimerVille(Request $request,
//                                EntityManagerInterface $entityManager,
//                                VilleRepository $villeRepository): Response
//    {
//
//        $idVille = $request->get('idVille');
//
//        $ville = $entityManager->find(Ville::class, $idVille);
//
//        if (!empty($ville)){
//            try {
//                $entityManager->remove($ville);
//                $entityManager->flush();
//
//                return new JsonResponse([
//                    "status" => "deleted"
//                ], 200);
//            }catch (\Doctrine\DBAL\Exception $exception){
//                $erreur = $exception->getMessage();
//                return new JsonResponse([
//                    "status" => "impossible"
//                ], 400);
//            }
//        }
//    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/recup_ville", name="recupVille")
     */
    public function recupVille(Request $request,
                               SerializerInterface $serializer,
                                   EntityManagerInterface $entityManager,
                                   VilleRepository $villeRepository): Response
    {

        $idVille = $request->get('idVille');

        $ville = $entityManager->find(Ville::class, $idVille);

        if (!empty($ville)){

            $json = $serializer->serialize($ville, 'json');

            return new JsonResponse($json, 200, [], true);
        }

    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/modif_ville", name="modifVille")
     */
    public function modifVille(Request $request,
                               SerializerInterface $serializer,
                               EntityManagerInterface $entityManager,
                               VilleRepository $villeRepository): Response
    {

        $idVille = $request->get('idVille');
        $nomVille = $request->get('nomVille');
        $cpVille = $request->get('cpVille');

        $ville = $entityManager->find(Ville::class, $idVille);

        if(!empty($nomVille)){
            $ville->setNom($nomVille);
        }
        if(!empty($cpVille)){
            $ville->setCodePostal($cpVille);
        }

        if(!empty($ville->getNom())){
            if (!empty($ville->getCodePostal())){

                //déclenche l'update en bdd
                $entityManager->persist($ville);
                $entityManager->flush();

                $json = $serializer->serialize($ville, 'json');

                return new JsonResponse($json, 200, [], true);;

            }
        }
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/rechercher_ville", name="rechercherVille")
     */
    public function rechercherVille(Request $request,
                               SerializerInterface $serializer,
                               EntityManagerInterface $entityManager,
                               VilleRepository $villeRepository): Response
    {

        $keyword = $_GET['keyword'];

        $villes = $villeRepository->search($keyword);

        //on convertit les champs du groupe wish_list en json
        $json = $serializer->serialize($villes, 'json');
        //le true sert à spécifier que nos données sont déjà en JSON
        return new JsonResponse($json, 200, [], true);

    }

    /**
     * @IsGranted ("ROLE_ADMIN")
     * @Route ("/admin/suppr_ville", name="suppr_ville")
     */
    public function supprVille(Request $request,
                                    EntityManagerInterface $entityManager,
                                    VilleRepository $villeRepository): Response
    {
        //Récupération de l'ID passé à ville.js après clic sur le bouton Supprimer
        $idVille = $request->get('idVille');

        //Récupération de l'objet Ville que l'admin veut supprimer
        $ville = $entityManager->find(Ville::class, $idVille);

        //Si la ville a été trouvée, elle est supprimée de la BDD,
        if (!empty($ville)){
            try {
                $entityManager->remove($ville);
                $entityManager->flush();

                //et un message de confirmation complète la requête AJAX.
                return new JsonResponse([
                    "status" => "deleted"
                ], 200);

                //En cas d'échec lors de l'accès à la BDD, la requête AJAX est déclarée impossible.
            }catch (\Doctrine\DBAL\Exception $exception){
                $erreur = $exception->getMessage();
                return new JsonResponse([
                    "status" => "impossible"
                ], 400);
            }
        }

    }

}
