<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleFormType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
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


            //déclenche l'update en bdd
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
}
