<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase {

    // Récupère le repository
    public function recupRepository(): FormationRepository {
        self::bootKernel();
        return self::getContainer()->get(FormationRepository::class);
    }

    // Crée une playlist de test
    private function newPlaylist(): Playlist {
        $playlist = new Playlist();
        $playlist->setName("Test Playlist");
        return $playlist;
    }

    // Crée une formation de test avec une playlist
    private function newFormation(Playlist $playlist): Formation {
        return (new Formation())
                        ->setTitle("Test Formation")
                        ->setPublishedAt(new \DateTime())
                        ->setPlaylist($playlist);
    }

    // Test Add / Remove
    public function testAddAndRemove() {
        $repository = $this->recupRepository();
        $em = self::getContainer()->get('doctrine')->getManager();

        $playlist = $this->newPlaylist();
        $em->persist($playlist);
        $em->flush();

        $formation = $this->newFormation($playlist);
        $repository->add($formation);

        $this->assertContains($formation, $repository->findAll(), "L'ajout a échoué");

        $repository->remove($formation);
        $this->assertNotContains($formation, $repository->findAll(), "La suppression a échoué");
    }

    // Test FindAllOrderBy
    public function testFindAllOrderBy() {
        $repository = $this->recupRepository();
        $formations = $repository->findAllOrderBy("publishedAt", "ASC");

        $this->assertNotEmpty($formations, "La liste ne doit pas être vide");
        if (count($formations) >= 2) {
            $this->assertTrue(
                    $formations[0]->getPublishedAt() <= $formations[1]->getPublishedAt(),
                    "Tri ASC incorrect"
            );
        }

        $formationsDesc = $repository->findAllOrderBy("publishedAt", "DESC");
        if (count($formationsDesc) >= 2) {
            $this->assertTrue(
                    $formationsDesc[0]->getPublishedAt() >= $formationsDesc[1]->getPublishedAt(),
                    "Tri DESC incorrect"
            );
        }
    }

    // Test FindByContainValue
    public function testFindByContainValue() {
        $repository = $this->recupRepository();
        $em = self::getContainer()->get('doctrine')->getManager();

        $playlist = $this->newPlaylist();
        $em->persist($playlist);
        $em->flush();

        $formation = $this->newFormation($playlist);
        $repository->add($formation);

        $result = $repository->findByContainValue("title", "Test Formation");
        $this->assertNotEmpty($result, "Recherche par valeur existante échoue");

        $resultEmpty = $repository->findByContainValue("title", "Formation inexistante");
        $this->assertEmpty($resultEmpty, "Recherche par valeur inexistante échoue");
    }

    // Test FindAllLasted
    public function testFindAllLasted() {
        $repository = $this->recupRepository();
        $result = $repository->findAllLasted(5);

        $this->assertNotEmpty($result, "La liste ne doit pas être vide");
        $this->assertLessThanOrEqual(5, count($result), "La méthode retourne plus que le nombre demandé");

        $this->assertTrue($result[0]->getPublishedAt() >= $result[1]->getPublishedAt(), "Tri DESC incorrect");
    }

    // Test FindAllForOnePlaylist
    public function testFindAllForOnePlaylist() {
        $repository = $this->recupRepository();
        $em = self::getContainer()->get('doctrine')->getManager();

        $playlist = $this->newPlaylist();
        $em->persist($playlist);
        $em->flush();

        $formation = $this->newFormation($playlist);
        $repository->add($formation);

        $formations = $repository->findAllForOnePlaylist($playlist->getId());

        $this->assertNotEmpty($formations, "Aucune formation trouvée pour cette playlist");
        $this->assertEquals(
                $playlist->getId(),
                $formations[0]->getPlaylist()->getId(),
                "Formation avec mauvais playlistId"
        );
    }
}
