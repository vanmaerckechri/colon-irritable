<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
	public function login(AuthenticationUtils $authenticationUtils, Request $request)
	{
		$user = $this->getUser();

		if (!is_null($user))
		{
        	return $this->redirectToRoute('recipe.index');
		}

		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('security/login.html.twig', [
			'last_username' => $lastUsername,
			'error' => $error
		]);
	}

	public function reset()
	{
		/*
		$user = $this->getUser();

		if (!is_null($user))
		{
        	return $this->redirectToRoute('recipe.index');
		}

		else
		{
			if ($user->getIsActive() === false)
			{
				$autoReplyByMail->sendActivationLink($user);
	        	$this->addFlash('success', 'Votre compte n\'est pas encore activé! Un nouveau mail vient de vous être envoyé. Veuillez cliquer sur son lien d\'activation pour pouvoir accéder à votre compte.');
	        	return $this->redirectToRoute('recipe.index');
			}
			else
			{
				$autoReplyByMail->sendActivationLink($user);
				$this->addFlash('success', 'Un mail vient de vous être envoyé. Veuillez cliquer sur son lien pour réinitialisé votre mot de passe.');
	        	return $this->redirectToRoute('recipe.index');
			}
		}

		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('security/login.html.twig', [
			'last_username' => $lastUsername,
			'error' => $error
		]);
		*/
	}
}