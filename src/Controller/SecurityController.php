<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;



class SecurityController extends AbstractController
{
	public function login(AuthenticationUtils $authenticationUtils, Request $request)
	{
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastPseudo = $authenticationUtils->getLastUsername();

		dump($request);

		return $this->render('security/login.html.twig', [
			'last_pseudo' => $lastPseudo,
			'error' => $error
		]);
	}
}