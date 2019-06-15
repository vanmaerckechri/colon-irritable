<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	public function login(AuthenticationUtils $authenticationUtils)
	{
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastPseudo = $authenticationUtils->getLastUsername();
		return $this->render('security/login.html.twig', [
			'last_pseudo' => $lastPseudo,
			'error' => $error
		]);
	}
}