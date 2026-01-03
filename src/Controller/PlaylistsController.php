<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des playlists
 *
 * @author emds
 */
class PlaylistsController extends AbstractController {

    /**
     * Chemin du template des playlists.
     */
    private const LINKPLAYLIST = 'pages/playlists.html.twig';

    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * @params PlaylistRepository $playlistRepository
     * @params FormationRepository $formationRepository
     * @params CategorieRepository $categorieRepository
     */
    public function __construct(PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository,
            FormationRepository $formationRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    /**
     * Calcule le nombre de formations par playlist
     *
     * @param iterable $playlists Liste des playlists
     *
     * @return array Tableau [idPlaylist => nombreDeFormations]
     */
    private function nbformations($playlists): array {
        $nombreformations = [];
        foreach ($playlists as $playlist) {
            $playlistId = $playlist->getId();
            $formations = $this->formationRepository->findAllForOnePlaylist($playlistId);
            $nombreformations[$playlistId] = count($formations);
        }
        return $nombreformations;
    }

    /**
     * Affiche la liste des playlists
     *
     * @return Response
     */
    #[Route('/playlists', name: 'playlists')]
    public function index(): Response {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        $nombreformations = $this->nbformations($playlists);
        return $this->render(self::LINKPLAYLIST, [
                    'playlists' => $playlists,
                    'categories' => $categories,
                    'nombreformation' => $nombreformations
        ]);
    }

    /**
     * Trie les playlists selon un critère donné.
     *
     * @param string $champ Champ de tri
     * @param string $ordre Ordre de tri (ASC ou DESC)
     *
     * @return Response
     */
    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
    public function sort($champ, $ordre): Response {
        switch ($champ) {
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nombreformation":
                $playlists = $this->playlistRepository->findAllOrderByNumberFormations($ordre);
                break;
            default:
                $playlists = $this->playlistRepository->findAll();
        }
        $nombreformations = $this->nbformations($playlists);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::LINKPLAYLIST, [
                    'playlists' => $playlists,
                    'categories' => $categories,
                    'nombreformation' => $nombreformations
        ]);
    }

    /**
     * Affiche les playlists avec l'outil de recherche
     *
     * @param string $champ Champ de recherche
     * @param Request $request Requête HTTP
     * @param string $table Table associée (catégories ici)
     *
     * @return Response
     */
    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        $nombreformations = $this->nbformations($playlists);
        return $this->render(self::LINKPLAYLIST, [
                    'playlists' => $playlists,
                    'categories' => $categories,
                    'valeur' => $valeur,
                    'table' => $table,
                    'nombreformation' => $nombreformations
        ]);
    }

    /**
     * Affiche les informations d'une playlist spécifique
     *
     * @param int $id Identifiant de la playlist
     * @return Response
     */
    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
    public function showOne($id): Response {
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
                    'playlist' => $playlist,
                    'playlistcategories' => $playlistCategories,
                    'playlistformations' => $playlistFormations,
                    'nombreformation' => count($playlistFormations)
        ]);
    }
}
