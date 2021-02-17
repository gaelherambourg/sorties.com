<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusFormType;
use App\Form\SearchCampusType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CampusController extends AbstractController
{

    /**
     * @IsGranted ("ROLE_ADMIN")
     * @Route("/admin/campus", name="campus")
     */
    public function listerCampus(Request $request,
                                 EntityManagerInterface $entityManager,
                                 CampusRepository $campusRepository): Response
    {
        //Création du formulaire de recherche
        $form = $this->createForm(SearchCampusType::class);
        $form->handleRequest($request);



        //Sinon, instanciation de l'entité Campus
        $campus = new Campus();




        //création d'une ligne formulaire dans le tableau, pour Ajout nouveau campus
        $form_campus = $this->createForm(CampusFormType::class, $campus);
        //Récupération des données du tableau-form pour hydrater la nouvelle instanciation
        $form_campus->handleRequest($request);

        //Si validation du formulaire de recherche, récupération des campus en BDD
        if($form->isSubmitted() && $form->isValid())
        {
            $rechercheUser = $form->get('recherche')->getData();

            $campusRecherches =$campusRepository->search($rechercheUser);

            return $this->render('campus/gererCampus.html.twig',[
                'tousCampus'=> $campusRecherches,
                'baseVide'=> 'Aucun campus en base ne correspond à votre recherche.',
                'form_searchC' => $form->createView(),
                'form_campus' => $form_campus->createView(),
            ]);
        }



        //et on liste tous les campus en base
        $tousCampus = $campusRepository->findAll();

        //Si soumission du formulaire d'Ajout valide :
        if($form_campus->isSubmitted() && $form_campus->isValid()) {

            //le nouveau campus est crée en BDD,
            $entityManager->persist($campus);
            $entityManager->flush();

            //et un message Flash en informe l'utilisateur,
            $this->addFlash('success', 'Le campus a bien été ajoutée en base.');

            //sur la même page actualisée où il est redirigé.
            return $this->redirectToRoute('campus');
        }


        return $this->render('campus/gererCampus.html.twig', [
            'form_campus' => $form_campus->createView(),
            'tousCampus' => $tousCampus,
            'form_searchC' => $form->createView(),
            'baseVide' => 'Il n\'y a aucun campus en base.'
        ]);
    }



    /**
     * @IsGranted ("ROLE_ADMIN")
     * @Route ("/admin/modif_campus", name="modifCampus")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param CampusRepository $campusRepository
     * @return Response
     */
    public function modifCampus(Request $request,
                                EntityManagerInterface $entityManager,
                                SerializerInterface $serializer,
                                CampusRepository $campusRepository): Response
    {
        //Récupération de l'ID dont le bouton a été cliqué
        $idCampus = $request->get('idCampus');
        //Récupération du nouveau nom inscrit dans le formulaire de modification
        $nomCampus = $request->get('nomCampus');

        //Récupération en BDD du Campus désigné
        $campus = $entityManager->find(Campus::class, $idCampus);

        //En cas de nouvelle proposition, on remplace le nom,
        if(!empty($nomCampus)){
            $campus->setNom($nomCampus);
        }

        if(!empty($campus->getNom())){
            //puis on déclenche l'update en BDD.
            $entityManager->persist($campus);
            $entityManager->flush();

            //Puis on le transforme en réponse Json pour affichage dynamique.
            $json = $serializer->serialize($campus, 'json');

            return  new JsonResponse($json, 200, [], true);
        }
    }

    /**
     * @IsGranted ("ROLE_ADMIN")
     * @Route ("/admin/recup_campus", name="recupCampus")
     */
    public function recupCampus(Request $request,
                                SerializerInterface $serializer,
                                EntityManagerInterface $entityManager,
                                CampusRepository $campusRepository): Response
    {
        $idCampus = $request->get('idCampus');

        $campus= $entityManager->find(Campus::class, $idCampus);

        if (!empty($campus)){

            $json = $serializer->serialize($campus, 'json');

            return new JsonResponse($json, 200, [], true);
        }
    }

    /**
     * @IsGranted ("ROLE_ADMIN")
     * @Route ("/admin/suppr_campus", name="supprCampus")
     */
    public function supprimerCampus(Request $request,
                                EntityManagerInterface $entityManager,
                                CampusRepository $campusRepository): Response
    {
        //Récupération de l'ID passé à campus.js après clic sur le bouton Supprimer
        $idCampus = $request->get('idCampus');

        //Récupération de l'objet Campus que l'admin veut supprimer
        $campus = $entityManager->find(Campus::class, $idCampus);

        //Si le campus a été trouvé, il est supprimé de la BDD,
        if (!empty($campus)){
            try {
                $entityManager->remove($campus);
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
