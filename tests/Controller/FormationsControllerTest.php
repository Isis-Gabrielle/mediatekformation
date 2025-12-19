<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormationsControllerTest extends WebTestCase
{
    /**
     * contrôle de l'accès à la page des formations
     */
    public function testPageFormationsAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseIsSuccessful();
    }

    /**
     * contrôle du contenu de la table et des en-têtes
     */
    public function testContenuPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        
        // vérification de la présence d'une colonne spécifique
        $this->assertSelectorTextContains('th', 'formation');
        // vérification du nombre total de colonnes
        $this->assertCount(5, $crawler->filter('th'));
    }

    /**
     * contrôle du fonctionnement du tri sur les titres
     */
    public function testTriTitle()
    {
        $client = static::createClient();

        // test du tri ascendant
        $crawlerAsc = $client->request('GET', '/formations/tri/title/ASC');
        $this->assertResponseIsSuccessful();
        $firstTitleAsc = $crawlerAsc->filter('h5')->first()->text();

        // test du tri descendant
        $crawlerDesc = $client->request('GET', '/formations/tri/title/DESC');
        $this->assertResponseIsSuccessful();
        $firstTitleDesc = $crawlerDesc->filter('h5')->first()->text();

        // vérification que l'ordre a bien changé entre les deux tris
        $this->assertNotEquals($firstTitleAsc, $firstTitleDesc);
    }

    /**
     * contrôle du fonctionnement du filtre par titre
     */
    public function testFiltreTitle()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');

        // récupération d'une valeur existante pour le test
        $valeur = $crawler->filter('h5')->first()->text();

        // soumission du formulaire de recherche
        $crawler = $client->submitForm('filtrer', [
            'recherche' => $valeur
        ]);

        // vérification du nombre de résultats et de la correspondance du titre
        $this->assertGreaterThanOrEqual(1, $crawler->filter('h5')->count());
        $this->assertSelectorTextContains('h5', $valeur);
    }

    /**
     * contrôle du lien vers la page de détail d'une formation
     */
    public function testLienVersDetail()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');

        // mémorisation du titre pour la vérification finale
        $titleText = trim($crawler->filter('h5')->first()->text());

        // clic sur le lien de la première formation
        $link = $crawler->filter('tbody tr td a')->first()->link();
        $client->click($link);

        // vérification de l'accès et de l'url de destination
        $this->assertResponseIsSuccessful();
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertStringContainsString('/formations/formation/', $uri);

        // vérification que le contenu de la page détail est correct
        $this->assertSelectorTextContains('h4.text-info', $titleText);
    }
}