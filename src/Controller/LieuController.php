<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class LieuController extends AbstractController
{
    /**
     * @Route("/lieu/infos_Lieu", name="change_infos_lieu", methods={"GET"})
     */
    public function infosLieu(Request $request,
                              LieuRepository $lieuRepository, SerializerInterface $serializer): Response
    {
        $setVal = $_GET['selVal'];

        $lieu = $lieuRepository->find($setVal);


        $json = $serializer->serialize($lieu, 'json');

        return new JsonResponse($json, 200, [], true);

    }

    /**
     * @Route("/lieu/changeLieu", name="change_select_lieu")
     */
    public function changeSelectLieu(Request $request,
                                     LieuRepository $lieuRepository, SerializerInterface $serializer): Response
    {
        $setVal = $_GET['selVal'];

        $lieux = $lieuRepository->rechercherLieuxParVille($setVal);


        $json = $serializer->serialize($lieux, 'json');

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/lieu/ajout_Lieu", name="ajout_lieu")
     */
    public function ajoutLieu(Request $request,
                              LieuRepository $lieuRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $nom = $_GET['nom'];
        $rue = $_GET['rue'];
        $latitude = $_GET['latitude'];
        $longitude = $_GET['longitude'];
        $idVille = $_GET['idVille'];

        $nouveauLieu = new Lieu();
        $nouveauLieu->setNom($nom);
        $nouveauLieu->setRue($rue);
        $nouveauLieu->setLatitude($latitude);
        $nouveauLieu->setLongitude(($longitude));
        $nouveauLieu->setVillesNoVille($entityManager->find(Ville::class, $idVille));

        $entityManager->persist($nouveauLieu);
        $entityManager->flush();


        $json = $serializer->serialize($nouveauLieu, 'json');

        return new JsonResponse($json, 200, [], true);

    }
}
