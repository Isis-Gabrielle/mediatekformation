<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccueilControllerTest extends WebTestCase
{
    /**
     * contrôle de l'accès à la page d'accueil
     */
    public function testPageAccueilAccessible()
    {
        // création du client et requête sur l'url racine
        $client = static::createClient();
        $client->request('GET', '/');
        // vérification du code de réponse succès
        $this->assertResponseIsSuccessful();
    }

    /**
     * contrôle des éléments textuels et du nombre de formations
     */
    public function testContenuAccueil()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // vérification du titre principal
        $this->assertSelectorTextContains('h3', 'Bienvenue sur le site de MediaTek86');
        
        // vérification de la présence de exactement deux formations
        $this->assertCount(2, $crawler->filter('h5.text-info'));
    }

    /**
     * contrôle du lien vers le détail d'une formation
     */
    public function testLienDetailFormationAccueil()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // récupération du nom de la première formation pour comparaison
        $formationNode = $crawler->filter('h5.text-info')->first();
        $formationTitle = trim($formationNode->text());

        // clic sur le premier lien trouvé dans le tableau
        $link = $crawler->filter('table a')->first()->link();
        $client->click($link);

        // vérification que la page de destination est accessible
        $this->assertResponseIsSuccessful();

        // vérification que le titre de la formation est bien affiché dans le détail
        $this->assertSelectorTextContains('h4', $formationTitle);
    }
}