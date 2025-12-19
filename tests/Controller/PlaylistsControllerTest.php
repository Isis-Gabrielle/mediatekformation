<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlaylistsControllerTest extends WebTestCase
{
    /**
     * contrôle de l'accès à la page des playlists
     */
    public function testPagePlaylistsAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseIsSuccessful();
    }

    /**
     * contrôle du fonctionnement du tri sur les noms des playlists
     */
    public function testTriPlaylist()
    {
        $client = static::createClient();

        // test du tri par nom ascendant
        $crawlerAsc = $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertResponseIsSuccessful();
        $firstNameAsc = trim($crawlerAsc->filter('h5')->first()->text());

        // test du tri par nom descendant
        $crawlerDesc = $client->request('GET', '/playlists/tri/name/DESC');
        $this->assertResponseIsSuccessful();
        $firstNameDesc = trim($crawlerDesc->filter('h5')->first()->text());

        // vérification que le premier élément change selon l'ordre
        $this->assertNotEquals($firstNameAsc, $firstNameDesc);
    }

    /**
     * contrôle du filtre par nom et du nombre de résultats
     */
    public function testFiltrePlaylist()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');

        // récupération d'un nom de playlist existant pour le filtre
        $playlistName = trim($crawler->filter('h5')->first()->text());

        // soumission du formulaire de recherche
        $crawler = $client->submitForm('filtrer', [
            'recherche' => $playlistName
        ]);

        // vérification de la présence de résultats et correspondance du nom
        $this->assertGreaterThanOrEqual(1, $crawler->filter('tbody tr')->count());
        $this->assertSelectorTextContains('h5', $playlistName);
    }

    /**
     * contrôle du clic sur le bouton de détail et du contenu de destination
     */
    public function testLienVersDetailPlaylist()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');

        // mémorisation du nom de la playlist
        $playlistName = trim($crawler->filter('h5')->first()->text());
        
        // clic sur le bouton voir détail
        $link = $crawler->selectLink('Voir détail')->first()->link();
        $client->click($link);

        // vérification de l'accès à la page de détail
        $this->assertResponseIsSuccessful();

        // vérification que le nom de la playlist est présent dans le titre h4
        $this->assertSelectorTextContains('h4', $playlistName);
    }
}