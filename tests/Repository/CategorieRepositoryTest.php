<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategorieRepositoryTest extends KernelTestCase {

    private function recupRepository(): CategorieRepository {
        self::bootKernel();
        return self::getContainer()->get(CategorieRepository::class);
    }

    private function newPlaylist(): Playlist {
        $playlist = new Playlist();
        $playlist->setName("Test Playlist");
        return $playlist;
    }

    private function newCategorie(): Categorie {
        $categorie = new Categorie();
        $categorie->setName("Test Categorie");
        return $categorie;
    }

    private function newFormation(Playlist $playlist): Formation {
        return (new Formation())
                        ->setTitle("Test Formation")
                        ->setPublishedAt(new DateTime("2025-12-18"))
                        ->setPlaylist($playlist);
    }

    public function testAddAndRemove() {
        $repository = $this->recupRepository();
        $em = self::getContainer()->get('doctrine')->getManager();

        $categorie = $this->newCategorie();
        $em->persist($categorie);
        $em->flush();

        $this->assertContains($categorie, $repository->findAll(), "L'ajout a échoué");

        $repository->remove($categorie);
        $this->assertNotContains($categorie, $repository->findAll(), "La suppression a échoué");
    }

    public function testFindAllForOnePlaylist() {
        $repository = $this->recupRepository();
        $em = self::getContainer()->get('doctrine')->getManager();

        // Créer et persister la playlist
        $playlist = $this->newPlaylist();
        $em->persist($playlist);

        // Créer et persister la catégorie
        $categorie = $this->newCategorie();
        $em->persist($categorie);

        // Créer et persister la formation
        $formation = $this->newFormation($playlist);
        $formation->addCategory($categorie);
        $em->persist($formation);

        $em->flush();

        // Test de la méthode du repository
        $result = $repository->findAllForOnePlaylist($playlist->getId());

        $this->assertNotEmpty($result, "Aucune catégorie trouvée pour cette playlist");
        $this->assertEquals(
                $categorie->getId(),
                $result[0]->getId(),
                "La catégorie retournée n'est pas correcte"
        );
    }
}
