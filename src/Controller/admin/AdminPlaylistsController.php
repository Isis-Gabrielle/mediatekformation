<?php
namespace App\Controller\admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPlaylistsController extends AbstractController{
   
    private const LINKADMINPLAYLIST = 'admin/playlist/admin.playlists.html.twig';
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
    
    public function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }
    private function nbformations ($playlists): array {
        $nombreformations = [];
                foreach($playlists as $playlist){
                    $playlistId = $playlist->getId();
                    $formations = $this->formationRepository->findAllForOnePlaylist($playlistId);
                    $nombreformations[$playlistId] = count($formations);
                }
        return $nombreformations;
    }
    
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        $nombreformations = $this->nbformations($playlists);
        return $this->render(self::LINKADMINPLAYLIST, [
            'playlists' => $playlists,
            'categories' => $categories,
            'nombreformation' => $nombreformations
        ]);
    }
    
    #[Route('/admin/playlist/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(int $id, Request $request): Response{
        $playlist = $this->playlistRepository->find($id);
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
           $this->playlistRepository->add($playlist);
        return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("admin/playlist/admin.playlist.edit.html.twig",
                ['playlist' => $playlist,
                    'playlistformations' => $playlistFormations,
                    'formPlaylist' => $formPlaylist->createView()]);
    }
    
    #[Route('/admin/playlist/ajout', name: 'admin.playlist.add')]
    public function ajout(Request $request): Response{
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
           $this->playlistRepository->add($playlist);
        return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("admin/playlist/admin.playlist.add.html.twig",
                ['playlist' => $playlist,
                    'formPlaylist' => $formPlaylist->createView()]);
    }
    
    #[Route('/admin/playlist/suppr/{id}', name: 'admin.playlist.delete')]
    public function delete(int $id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        $nombreformations = count($playlistFormations);
        if($nombreformations > 0){
            throw new \InvalidArgumentException("Il reste encore des formations affiliées à la playlist.");             
        }
        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute('admin.playlists');
    }

#[Route('admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response{
        switch($champ){
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
        return $this->render(self::LINKADMINPLAYLIST, [
            'playlists' => $playlists,
            'categories' => $categories,
            'nombreformation' => $nombreformations
        ]);
    }     
    
     #[Route('admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        $nombreformations = $this->nbformations($playlists);
        return $this->render(self::LINKADMINPLAYLIST, [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table,
            'nombreformation' => $nombreformations
        ]);
    }
}
