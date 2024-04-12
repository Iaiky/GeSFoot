<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Joueur;
use App\Entity\National;
use App\Form\ClubSelectType;
use App\Form\JoueurType;
use App\Form\SelectClubType;
use App\Form\SelectNationalType;
use App\Repository\JoueurRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



class GestionDeJoueursController extends AbstractController
{

    #[Route('/gestion/de/joueurs/tri/{tri}', name: 'app_gestion_de_joueurs')]
    public function index($tri,JoueurRepository $joueur, Request $request): Response
    {
        // Form filtrage selon national formulaire
        $formNational = $this->createForm(SelectNationalType::class);
        $formNational->handleRequest($request);
        if ($formNational->isSubmitted() && $formNational->isValid()) {
            // Traitement des données du formulaire
            $nationalId = $formNational->get('national')->getData()->getId();


            // Faire quelque chose avec le club sélectionné, par exemple rediriger vers une autre page
            return $this->redirectToRoute('joueur_par_national', ['id' => $nationalId]);
        }

        // Form filtrage selon club formulaire
        $formClub = $this->createForm(SelectClubType::class);
        $formClub->handleRequest($request);
        if ($formClub->isSubmitted() && $formClub->isValid()) {
            // Traitement des données du formulaire
            $clubId = $formClub->get('club')->getData()->getId();


            // Faire quelque chose avec le club sélectionné, par exemple rediriger vers une autre page
            return $this->redirectToRoute('joueur_par_club', ['id' => $clubId]);
        }

        if($tri == 1) {
            $player = $joueur->findAll();
        }elseif($tri == 2){
            $player = $joueur->findAllSortedByName();
        }elseif($tri == 3){
            $player = $joueur->findAllSortedByNombreDeButs();
        }else{
            $player = $joueur->findAllSortedByParcours();
        }
            
        

        return $this->render('gestion_de_joueurs/index.html.twig', [
            'controller_name' => 'GestionDeJoueursController',
            // 'joueurs' => $joueur->findAll(),
            'joueurs' => $player,
            'formNational' => $formNational->createView(),
            'formClub' => $formClub->createView()
        ]);
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('gestion_de_joueurs/home.html.twig',[
            'titre' => 'Gestion de Joueurs de football',
        ]);
    }

    #[Route('/gestion/de/joueurs/ajout', name: 'joueur_ajout')]
    public function ajout(Request $request, ObjectManager $manager): Response
    {
        $joueur = new Joueur();


        $form = $this->createForm(JoueurType::class, $joueur);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($joueur);
            $manager->flush();

            return $this->redirectToRoute('fiche_joueur', ['id' => $joueur->getId()]);

        }

        return $this->render('gestion_de_joueurs/ajout.html.twig',[
            'formJoueur' => $form->createView(),
            'buttontext' => 'Ajouter',
            'titre' => 'Ajout',
        ]);
    }

    #[Route('/gestion/de/joueurs/supprimer/{id}', name: 'joueur_supprimer')]
    public function supprimer($id, ObjectManager $manager): Response
    {
        $club = $manager->getRepository(Joueur::class)->find($id);

        if (!$club) {
            throw $this->createNotFoundException('Joueur non trouvé');
        }

        $manager->remove($club);
        $manager->flush();

        return $this->redirectToRoute('app_gestion_de_joueurs', ['tri' => 1]);
    }

    #[Route('/gestion/de/joueurs/club/{id}', name: 'joueur_par_club')]
    public function joueurParClub($id, ObjectManager $manager, Request $request): Response
    {
        $club = $manager->getRepository(Club::class)->find($id);

        if (!$club) {
            throw $this->createNotFoundException('Joueur non trouvé');
        }

        $joueurs = $manager->getRepository(Joueur::class)->findBy(['club' => $club]);

        // Form filtrage selon national formulaire
        $formNational = $this->createForm(SelectNationalType::class);
        $formNational->handleRequest($request);
        if ($formNational->isSubmitted() && $formNational->isValid()) {
            // Traitement des données du formulaire
            $nationalId = $formNational->get('national')->getData()->getId();


            // Faire quelque chose avec le club sélectionné, par exemple rediriger vers une autre page
            return $this->redirectToRoute('joueur_par_national', ['id' => $nationalId]);
        }

        // Form filtrage selon club formulaire
        $formClub = $this->createForm(SelectClubType::class);
        $formClub->handleRequest($request);
        if ($formClub->isSubmitted() && $formClub->isValid()) {
            // Traitement des données du formulaire
            $clubId = $formClub->get('club')->getData()->getId();


            // Faire quelque chose avec le club sélectionné, par exemple rediriger vers une autre page
            return $this->redirectToRoute('joueur_par_club', ['id' => $clubId]);
        }

        return $this->render('gestion_de_joueurs/index.html.twig', [
            'controller_name' => 'GestionDeJoueursController',
            'joueurs' => $joueurs,
            'formNational' => $formNational->createView(),
            'formClub' => $formClub->createView()
        ]);

    }

    #[Route('/gestion/de/joueurs/national/{id}', name: 'joueur_par_national')]
    public function joueurParNational($id, ObjectManager $manager, Request $request): Response
    {
        $national = $manager->getRepository(National::class)->find($id);

        if (!$national) {
            throw $this->createNotFoundException('Joueur non trouvé');
        }

        $joueurs = $manager->getRepository(Joueur::class)->findBy(['national' => $national]);

        // Form filtrage selon national formulaire
        $formNational = $this->createForm(SelectNationalType::class);
        $formNational->handleRequest($request);
        if ($formNational->isSubmitted() && $formNational->isValid()) {
            // Traitement des données du formulaire
            $nationalId = $formNational->get('national')->getData()->getId();


            // Faire quelque chose avec le club sélectionné, par exemple rediriger vers une autre page
            return $this->redirectToRoute('joueur_par_national', ['id' => $nationalId]);
        }

        // Form filtrage selon club formulaire
        $formClub = $this->createForm(SelectClubType::class);
        $formClub->handleRequest($request);
        if ($formClub->isSubmitted() && $formClub->isValid()) {
            // Traitement des données du formulaire
            $clubId = $formClub->get('club')->getData()->getId();


            // Faire quelque chose avec le club sélectionné, par exemple rediriger vers une autre page
            return $this->redirectToRoute('joueur_par_club', ['id' => $clubId]);
        }

        return $this->render('gestion_de_joueurs/index.html.twig', [
            'controller_name' => 'GestionDeJoueursController',
            'joueurs' => $joueurs,
            'formNational' => $formNational->createView(),
            'formClub' => $formClub->createView()
        ]);

    }

    #[Route('/gestion/de/joueurs/{id}/edit', name: 'joueur_edit')]
    public function edit(Joueur $joueur, Request $request, ObjectManager $manager): Response
    {

        $form = $this->createForm(JoueurType::class, $joueur);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->flush();

            return $this->redirectToRoute('fiche_joueur', ['id' => $joueur->getId()]);
        }

        return $this->render('gestion_de_joueurs/ajout.html.twig', [
            'formJoueur' => $form->createView(),
            'buttontext' => 'Enregistrer les modifications',
            'titre' => 'Modification',
        ]);
    }

    #[Route('/gestion/de/joueurs/{id}', name: 'fiche_joueur', methods:['GET'])]
    public function afficher($id, JoueurRepository $joueur): Response
    {
        return $this->render('gestion_de_joueurs/fiche.html.twig',[
            'joueur' => $joueur->find($id),
        ]);
    }

    

}
