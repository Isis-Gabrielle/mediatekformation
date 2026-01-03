<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Contrôleur d’authentification.
 */
final class LoginController extends AbstractController
{
      /**
     * Affiche le formulaire de connexion
     *
     * @param AuthenticationUtils $authenticationUtils Utilitaires d’authentification
     * @return Response
     */
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $email = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'email' => $email,
            'error' => $error
        ]);
    }
    
     /**
     * Point d’entrée de la déconnexion
     *
     * @return void
     */
    #[Route('/logout', name: 'logout')]
    public function logout()
    {
        
    }
}
