<?php

namespace App\Controller;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Form\SearchType;
use App\Repository\VideoGamesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, HttpClientInterface $client): Response
    {   

        $formSearch = $this->createForm(SearchType::class);
        $formSearch ->handleRequest($request);
        if($formSearch->isSubmitted() && $formSearch->isValid()){
            $name = $formSearch['game']->getData();
            $key = 'bb3ab1cea0ff4dd5a74c621ad9cea8f3';
            $response = $client->request(
            'GET',
            "https://api.rawg.io/api/games?key=".$key."&search=".$name
            );
            $result = $response->toArray();
            return $this->render('video_games/index.html.twig', [
                'result' => $result['results'],
                'name' => $name,
                'formFavorite' => $formSearch->createView()
            ]);
        }

        if($this->getUser()){
            $connect = 1;
            $favGameUser = $this->getUser()->getGameID();
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'formSearch' => $formSearch->createView(),
                'connexion' => $connect,
                'games' => $favGameUser->toArray()
            ]);
        }else{
            $connect = 0;
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'formSearch' => $formSearch->createView(),
                'connexion' => $connect
            ]);
        }

    }
    #[Route('/del/{id}', name: 'app_delFavorite')]
    public function delFavorite(int $id, UserInterface $user,
    VideoGamesRepository $videoGamesRepository,
    EntityManagerInterface $entityManager){
        $user = $this->getUser();
        $gameCurrent = $videoGamesRepository->findOneBy(['id' => $id]);
        $user->removeGameID($gameCurrent);
        $entityManager->flush();
        return $this->redirectToRoute('app_home');
    }
    
}
