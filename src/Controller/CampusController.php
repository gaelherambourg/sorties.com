<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusFormType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        //Instanciation de l'entité Campus
        $campus = new Campus();

        //Liste de tous les campus en base
        $tousCampus = $campusRepository->findAll();

        //création d'une ligne formulaire dans le tableau, pour Ajout nouveau campus
        $form_campus = $this->createForm(CampusFormType::class, $campus);

        //Récupération des données du tableau-form pour hydrater la nouvelle instanciation
        $form_campus->handleRequest($request);

        //Si soumission d'un formulaire valide :
        if($form_campus->isSubmitted() && $form_campus->isValid()) {

            //le nouveau campus crée en BDD,
            $entityManager->persist($campus);
            $entityManager->flush();

            //et un message Flash en informe l'utilisateur,
            $this->addFlash('success', 'Le campus a bien été ajoutée en base.');

            //sur la même page actualisée où il est redirigé.
            return $this->redirectToRoute('campus');
        }


        return $this->render('campus/gererCampus.html.twig', [
            'form_campus' => $form_campus->createView(),
            'tousCampus' => $tousCampus
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
                                CampusRepository $campusRepository): Response
    {
        //Récupération du Campus dont le bouton a été cliqué

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
