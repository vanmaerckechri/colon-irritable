<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;



class SecurityController extends AbstractController
{
	public function login(AuthenticationUtils $authenticationUtils, Request $request)
	{
		if (!is_null($this->getUser()))
		{
        	$this->addFlash('success', 'Vous êtes déjà connecté à votre compte!');
        	return $this->redirectToRoute('recipe.index');
		}

		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('security/login.html.twig', [
			'last_username' => $lastUsername,
			'error' => $error
		]);
	}
}