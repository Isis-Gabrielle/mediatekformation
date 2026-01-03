<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsController extends AbstractController {

    /**
     * Chemin du template des formations.
     */
    private const LINKFORMATION = 'pages/formations.html.twig';

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
     * 
     * @params FormationRepository $formationRepository
     * @params CategorieRepository $categorieRepository
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Affiche la liste des formations
     *
     * @return Response
     */
    #[Route('/formations', name: 'formations')]
    public function index(): Response {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::LINKFORMATION, [
                    'formations' => $formations,
                    'categories' => $categories
        ]);
    }

    /**
     * Trie les formations
     *
     * @param string $champ Champ de tri
     * @param string $ordre Ordre de tri (ASC ou DESC)
     * @param string $table Table associée (catégories ici)
     * @return Response
     */
    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table = ""): Response {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::LINKFORMATION, [
                    'formations' => $formations,
                    'categories' => $categories
        ]);
    }

    /**
     * Affiche les formations avec l'outil de recherche
     *     
     * @param string $champ Champ de recherche
     * @param Request $request Requête HTTP
     * @param string $table Table associée(catégories ici)
     * @return Response
     */
    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::LINKFORMATION, [
                    'formations' => $formations,
                    'categories' => $categories,
                    'valeur' => $valeur,
                    'table' => $table
        ]);
    }

    /**
     * Affiche les informations d'une formation spécifique
     *
     * @param int $id Identifiant de la formation
     * @return Response
     */
    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response {
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
                    'formation' => $formation
        ]);
    }
}
