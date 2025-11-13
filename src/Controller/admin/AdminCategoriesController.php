<?php
namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController{
   
        /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    private $formationRepository;
    
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(Request $request): Response{
        $categories = $this->categorieRepository->findAll();
        $categorie = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        $formCategorie->handleRequest($request);
        if($formCategorie->isSubmitted() && $formCategorie->isValid()){
        $this->categorieRepository->add($categorie);
        return $this->redirectToRoute('admin.categories');
        }
        return $this->render("admin/categorie/admin.categories.html.twig", [
            'categories' => $categories,
            'formCategorie' => $formCategorie->createView()
        ]);
    }

    #[Route('/admin/categorie/suppr/{id}', name: 'admin.categorie.delete')]
    public function delete(int $id): Response{
        $categorie = $this->categorieRepository->find($id);
        $nombreformations = count($categorie->getFormations());
        if($nombreformations > 0){
            throw new \InvalidArgumentException("Il reste encore des formations affiliées à la catégorie.");             
        }
        $this->categorieRepository->remove($categorie);
        return $this->redirectToRoute('admin.categories');
    }

}
