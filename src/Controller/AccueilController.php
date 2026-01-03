<?php

namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur de l'accueil
 *
 * @author emds
 */
class AccueilController extends AbstractController {

    /**
     * @var FormationRepository
     */
    private $repository;

    /**
     * 
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * Affiche la page d’accueil.
     *
     * @return Response
     */
    #[Route('/', name: 'accueil')]
    public function index(): Response {
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
                    'formations' => $formations
        ]);
    }

    /**
     * Affiche la page CGU
     *
     * @return Response
     */
    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response {
        return $this->render("pages/cgu.html.twig");
    }
}
