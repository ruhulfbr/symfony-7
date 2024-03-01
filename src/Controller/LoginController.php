<?php
// src/Controller/LoginController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('auth/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('error', 'You are already logged in');
            return $this->redirectToRoute('app_user_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('auth/logout', name: 'app_logout')]
    public function logout(Security $security): void
    {
        $this->addFlash('success', 'Successfully logged out.');
        // logout the user in on the current firewall
        $response = $security->logout(false);

        // you can also disable the csrf logout
//        $response = $security->logout(false);

        // ... return $response (if set) or e.g. redirect to the homepage
    }
}
