<?php

namespace App\Controller;

use App\Entity\VideoGames;
use App\Repository\VideoGamesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class VideoGamesController extends AbstractController
{
    #[Route('/video/games', name: 'app_video_games')]
    public function index(): Response
    {
        return $this->render('video_games/index.html.twig', [
            'controller_name' => 'VideoGamesController',
        ]);
    }

    #[Route('/{id}', name: 'app_addFavorite')]
    public function addToFavorite(EntityManagerInterface $entityManager ,
    int $id, HttpClientInterface $client, UserInterface $user,
    VideoGamesRepository $videoGamesRepository
    ): Response {
        $key = 'bb3ab1cea0ff4dd5a74c621ad9cea8f3';
        // On fait appel à l'API pour obtenir les informations du jeu 
        $response = $client->request(
            'GET',
            "https://api.rawg.io/api/games/" . $id ."?key=" . $key
        );
        // On récupère la réponse puis on la converti en tableau exploitable
        $results = $response->toArray();

        // On instancie un objet de la classe VideoGames dans le but
        // de l'enregistrer dans la BDD après l'avoir hydraté
        $videoGame = new VideoGames;
        $videoGame
            ->setName($results['name'])
            ->setPlatform($results['parent_platforms'][0]['platform']['name'])
            ->setImgGame($results['background_image'])
            ->setReleaseDate($results['released'])
            ->setIdGameAPI($results['id'])
            ;

        // On récupère l'utilisateur courant
        $user = $this->getUser();
        // On vérifie si le jeu sélectionner n'existe pas déjà en BDD
        if(!$videoGamesRepository->findOneBy(['idGameAPI' => $results['id']])){
            // Si non alors on l'ajoute à la BDD et aux favori de l'utilisateur
            $entityManager->persist($videoGame);
            $user->addGameID($videoGame);
            $entityManager->flush();
        }else{
            // Si oui on récupère l'enregistrement existant et on l'ajoute
            // au favori de l'utilisateur courant
            $videoGameDataBase = $videoGamesRepository->findOneBy(['idGameAPI' => $results['id']]);
            $user->addGameID($videoGameDataBase);
            $entityManager->flush();
        }
        // Puis on renvoie sur la page d'accueil où est affichée la liste
        // des favoris de l'utilisateur courant
        return $this->redirectToRoute('app_home');

    }
   
}
