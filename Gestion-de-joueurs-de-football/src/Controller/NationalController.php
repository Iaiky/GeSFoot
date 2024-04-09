<?php

namespace App\Controller;

use App\Entity\National;
use App\Form\NationalType;
use App\Repository\NationalRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NationalController extends AbstractController
{
    #[Route('/national', name: 'app_national')]
    public function index(NationalRepository $national): Response
    {
        return $this->render('national/index.html.twig', [
            'controller_name' => 'NationalController',
            'nationals' => $national->findAll(),
        ]);
    }

    #[Route('/national/ajout', name: 'national_ajout')]
    public function ajout(Request $request, ObjectManager $manager): Response
    {
        $national = new National();

        $form = $this->createForm(NationalType::class, $national);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($national);
            $manager->flush();

            return $this->redirectToRoute('app_national');

        }

        return $this->render('national/ajout.html.twig',[
            'formNational' => $form->createView(),
            'buttontext' => 'Ajouter',
            'titre' => 'Ajout',
        ]);
    }

    #[Route('/national/supprimer/{id}', name: 'national_supprimer')]
    public function supprimer($id, ObjectManager $manager): Response
    {
        $national = $manager->getRepository(National::class)->find($id);

        if (!$national) {
            throw $this->createNotFoundException('équipe non trouvé');
        }

        $manager->remove($national);
        $manager->flush();

        return $this->redirectToRoute('app_national');
    }

    #[Route('/national/{id}/edit', name: 'national_edit')]
    public function edit(National $national, Request $request, ObjectManager $manager): Response
    {

        $form = $this->createForm(NationalType::class, $national);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->flush();

            return $this->redirectToRoute('app_national');
        }

        return $this->render('national/ajout.html.twig', [
            'formNational' => $form->createView(),
            'buttontext' => 'Enregistrer les modifications',
            'titre' => 'Modification',
        ]);
    }
}
