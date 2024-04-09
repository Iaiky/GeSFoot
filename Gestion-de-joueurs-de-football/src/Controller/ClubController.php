<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClubController extends AbstractController
{
    #[Route('/club', name: 'app_club')]
    public function index(ClubRepository $club): Response
    {
        return $this->render('club/index.html.twig', [
            'controller_name' => 'ClubController',
            'clubs' => $club->findAll(),
        ]);
    }

    #[Route('/club/ajout', name: 'club_ajout')]
    public function ajout(Request $request, ObjectManager $manager): Response
    {
        $club = new Club();

        $form = $this->createForm(ClubType::class, $club);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($club);
            $manager->flush();

            return $this->redirectToRoute('app_club');

        }

        return $this->render('club/ajout.html.twig',[
            'formClub' => $form->createView(),
            'buttontext' => 'Ajouter',
            'titre' => 'Ajout',
        ]);
    }

    #[Route('/club/supprimer/{id}', name: 'club_supprimer')]
    public function supprimer($id, ObjectManager $manager): Response
    {
        $club = $manager->getRepository(Club::class)->find($id);

        if (!$club) {
            throw $this->createNotFoundException('Club non trouvé');
        }

        $manager->remove($club);
        $manager->flush();

        return $this->redirectToRoute('app_club');
    }

    
    #[Route('/club/{id}/edit', name: 'club_edit')]
    public function edit(Club $club, Request $request, ObjectManager $manager): Response
    {

        $form = $this->createForm(ClubType::class, $club);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->flush();

            return $this->redirectToRoute('app_club');
        }

        return $this->render('club/ajout.html.twig', [
            'formClub' => $form->createView(),
            'buttontext' => 'Enregistrer les modifications',
            'titre' => 'Modification',
        ]);
    }
}
