<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Form\FormLieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @Route("/lieu/ajout_Lieu", name="ajout_lieu", methods={"POST"})
     */
    public function ajoutLieu(Request $request,
                              LieuRepository $lieuRepository,
                              SerializerInterface $serializer,
                              EntityManagerInterface $entityManager,
                              ValidatorInterface $validator): Response
    {
        $errors = [];

        $regChiffre = '/^-?\d+(?:[.,]\d+)?$/';

        $nouveauLieu = new Lieu();

        $form = $this->createForm(FormLieuType::class, $nouveauLieu);

        $nom = $request->get('nom');
        $rue = $request->get('rue');
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $idVille = $request->get('idVille');

        if (preg_match($regChiffre,$latitude)){
            $nouveauLieu->setLatitude($latitude);
        }else{
            array_push($errors, "Erreur lors de la saisie de la latitude");
        }
        if (preg_match($regChiffre,$longitude)){
            $nouveauLieu->setLongitude($longitude);
        }else{
            array_push($errors, "Erreur lors de la saisie de la longitude");
        }
        if (!empty($nom)){
            $nouveauLieu->setNom($nom);
        }else{
            array_push($errors, "Veuillez donner un nom au lieu");
        }
        $nouveauLieu->setRue($rue);
        $nouveauLieu->setVillesNoVille($entityManager->find(Ville::class, $idVille));

        if(empty($errors)){
            $entityManager->persist($nouveauLieu);
            $entityManager->flush();

            $json = $serializer->serialize($nouveauLieu, 'json');

            return new JsonResponse($json, 200, [], true);
        }else
        {
            $json2 = $serializer->serialize($errors, 'json');
            return new JsonResponse($json2, Response::HTTP_BAD_REQUEST, [], true);
        }

    }
}
