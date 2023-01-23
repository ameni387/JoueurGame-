<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Joueur;
use App\Form\GameType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Routing\Annotation\Route;


class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $game = new Game();
        $game->setTitre("world cup");
        $game->setType("baseball");
        $game->setnbrJoueur(11);
        $game->setEditeur("azerty");
        $game->setImagePath("https://images.unsplash.com/photo-1528291954423-c0c71c12baeb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1113&q=80");
        //Ajouter des Joueurs
        $joueur = new Joueur();
        $joueur->setNom("ted williams");
        $joueur->setEmail("ted@gmail.com");
        $joueur->setBornAt(new \DateTimeImmutable());
        $joueur->setScore(5);
        $joueur->setGame($game);
        $entityManager->persist($game);
        $entityManager->persist($joueur);
        $entityManager->flush();

        return $this->render('game/index.html.twig', [
            'id' =>$game->getId(),
            #'controller_name' => 'GameController',
        ]);
    }
  
    #[
        Route('/home', name: 'home'),
        ]
    public function home(){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Joueur::class);
        $lesJoueur = $repo->findAll();
        
        return $this->render('game/home.html.twig',
        ['lesJoueur'=>$lesJoueur]);

}
#[
    Route('/recherche', name: 'recherche'),
    ]
public function recherche(Request $request){
    $em = $this->getDoctrine()->getManager();
   
    $motcle=$request->get('RechercheNom');
    $repository = $em->getRepository(Joueur::class);
    $query = $repository->createQueryBuilder('j')
        ->where('j.nom like :nom')
        ->setParameter('nom', $motcle.'%')
        ->orderBy('j.nom','ASC')
        ->getQuery();
    $lesJoueur = $query->getResult();
    return $this->render('game/home.html.twig',
    ['lesJoueur'=>$lesJoueur]);
}

#[Route('/allGames', name: 'game')]
    public function homallGames(): Response{
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Game::class);
        $lesGame = $repo->findAll();
        return $this->render('game/index2.html.twig',
        ['lesGame'=>$lesGame]);
}
#[Route('/allGames/{id}', methods:['GET'], name: 'game_show')]
public function show($id): Response{
    $game = $this->getDoctrine()
          ->getRepository(Game::class)
          ->find($id);
        if(!$game){
            throw $this->createNotFoundException(`No game found for`.$id);    
        }
        return $this->render('game/show.html.twig',[
            'game'=>$game
        ]);
}
#[Route('/allJoueur/{id}', methods:['GET'], name: 'Joueur_show')]
public function show2($id): Response{
    $joueur = $this->getDoctrine()
          ->getRepository(Joueur::class)
          ->find($id);
        if(!$joueur){
            throw $this->createNotFoundException(`No joueur found for`.$id);    
        }
        return $this->render('joueur/show2.html.twig',[
            'joueur'=>$joueur
        ]);
}


#[Route('/edit/Game/{id?0}', name: 'edit_game')]
public function editGame(Request $request,Game $game= null)
{
    $new=false;
    if(!$game){
        $new=true;
        $game = new Game();
    }
    
    $form = $this->createForm("App\Form\GameType",$game);
    $form -> handleRequest($request);
    if($form->isSubmitted())
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($game);
        $em->flush();
        if($new){
         //$session = new Session();
         //$session->getFlashBag()->add('notice', 'Game ajouté avec succès');
         $message = 'Game a été ajouté avec succès';
        }else{
            $message = 'Game a été  mis à joure  avec succes';
         //$session = new Session();
        // $session->getFlashBag()->add('notice', 'Game mis à joure  avec succes');
        }
        $this->addFlash('success',$game->getTitre(). $message);
        return $this->redirectToRoute('game');
    }

    return $this->render('game/add.html.twig',
    ['f'=>$form->createView()]);
}
#[Route('/edit/Joueur/{id?0}', name: 'Ajouter')]
public function addJoueur(Request $request,Joueur $joueur=null)
{
    $new=false;
    if(!$joueur){
        $new=true;
        $joueur = new Joueur();
    }
    
    $form = $this->createForm("App\Form\JoueurType",$joueur);
    $form -> handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($joueur);
        $em->flush();
        $session = new Session();
        if($new){
            $session->getFlashBag()->add('notice', 'Joueur ajouté avec succes');
        }else{
            $session->getFlashBag()->add('notice', 'Joueur mis à joure avec succes');
        }
        
        return $this->redirectToRoute('home');
       
    }

    return $this->render('joueur/add.html.twig',
    ['f'=>$form->createView()]);
}
#[
    Route('/supp/{id}', name: 'joueur_delete'),
    IsGranted("ROLE_ADMIN")
    ]
public function supprimer($id): Response
    {
        $c = $this->getDoctrine()->getRepository(Joueur::class)->find($id);

        if (!$c) {
            throw $this->createNotFoundException(
                'No Joueur found' . $id
            );
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($c);
        //valider la suppression
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }

    #[
        Route('/suppG/{id}', name: 'Game_delete'),
        IsGranted("ROLE_ADMIN")
        ]
    public function supprimerGame($id): Response
        {
            $c = $this->getDoctrine()->getRepository(Game::class)->find($id);
    
            if (!$c) {
                throw $this->createNotFoundException(
                    'No Game found' . $id
                );
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($c);
            //valider la suppression
            $entityManager->flush();
            return $this->redirectToRoute('game');
        }
       
    }
   


