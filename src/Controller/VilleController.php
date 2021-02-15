<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleFormType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    /**
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

        return $this->render('ville/listerVille.html.twig', [
            'ville_form'=> $form_ville->createView(),
            'villes' => $villes,
        ]);
    }
}
