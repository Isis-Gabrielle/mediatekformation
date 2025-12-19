<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Entity\Formation;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{
    private function recupRepository(): PlaylistRepository {
        self::bootKernel();
        return self::getContainer()->get(PlaylistRepository::class);
    }

    private function newPlaylist(): Playlist {
        $playlist = new Playlist();
        $playlist->setName("Playlist Test");
        return $playlist;
    }

    private function newFormation(Playlist $playlist): Formation {
        $formation = new Formation();
        $formation->setTitle("Formation Test")
                  ->setPublishedAt(new \DateTime())
                  ->setPlaylist($playlist);
        return $formation;
    }

    public function testAddAndRemove() {
        $repo = $this->recupRepository();
        $em = self::getContainer()->get('doctrine')->getManager();

        $playlist = $this->newPlaylist();
        $em->persist($playlist);
        $em->flush();

        // Test add
        $repo->add($playlist);
        $this->assertContains($playlist, $repo->findAll(), "L'ajout a échoué");

        // Test remove
        $repo->remove($playlist);
        $this->assertNotContains($playlist, $repo->findAll(), "La suppression a échoué");
    }

    public function testFindAllOrderByName() {
        $repo = $this->recupRepository();
        $em = self::getContainer()->get('doctrine')->getManager();

        $playlist1 = (new Playlist())->setName("A Playlist");
        $playlist2 = (new Playlist())->setName("B Playlist");

        $em->persist($playlist1);
        $em->persist($playlist2);
        $em->flush();

        $resultAsc = $repo->findAllOrderByName('ASC');
        $this->assertTrue($resultAsc[0]->getName() <= $resultAsc[1]->getName(), "Tri ASC incorrect");

        $resultDesc = $repo->findAllOrderByName('DESC');
        $this->assertTrue($resultDesc[0]->getName() >= $resultDesc[1]->getName(), "Tri DESC incorrect");
    }

    public function testFindByContainValue() {
        $repo = $this->recupRepository();
        $em = self::getContainer()->get('doctrine')->getManager();

        $playlist = $this->newPlaylist();
        $em->persist($playlist);
        $em->flush();

        $result = $repo->findByContainValue("name", "Playlist Test");
        $this->assertNotEmpty($result, "Recherche par valeur existante échoue");

        $resultEmpty = $repo->findByContainValue("name", "Inexistante");
        $this->assertEmpty($resultEmpty, "Recherche par valeur inexistante échoue");
    }

    public function testFindAllOrderByNumberFormations() {
        $repo = $this->recupRepository();
        $em = self::getContainer()->get('doctrine')->getManager();

        $playlist = $this->newPlaylist();
        $em->persist($playlist);

        $formation1 = $this->newFormation($playlist);
        $formation2 = $this->newFormation($playlist);

        $em->persist($formation1);
        $em->persist($formation2);
        $em->flush();

        $result = $repo->findAllOrderByNumberFormations('DESC');
        $this->assertNotEmpty($result, "Le tri par nombre de formations a échoué");
    }
}
