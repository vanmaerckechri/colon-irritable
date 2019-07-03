<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use Symfony\Component\HttpFoundation\RequestStack;

class SendAccountCode extends AbstractController
{
	/**
	 * @var \Swift_mailer
	 */
	private $mailer;

	/**
	 * @var Environment
	 */
	private $renderer;

	/**
	 * @var string
	 */
	private $domaine;

	/**
	 * @var string
	 */
	private $hashing_method;

	public function __construct(\Swift_Mailer $mailer, Environment $renderer, RequestStack $requestStack)
	{
		$this->mailer = $mailer;
		$this->renderer = $renderer;
		$this->domaine = $requestStack->getCurrentRequest()->getSchemeAndHttpHost();
		$this->hashing_method = 'sha256';
	}

	public function sendActivationLink(User $user): void
	{
		$code = $this->updateCode($user);

		$link = $this->generateUrl('account.activation', [
			'code' => $code
		]);

		$link = $this->domaine . $link;

		$message = (new \Swift_Message('Valider l\'Inscription'))
			->setFrom('robot@lecolonirritable.com')
			->setTo($user->getMail())
			->setBody($this->renderer->render('emails/registerValidation.html.twig', [
				'member' => $user,
				'link' => $link,
			]), 'text/html');

		$this->mailer->send($message);
	}

	public function sendPasswordResetLink(User $user): void
	{

		$code = $this->updateCode($user);

		$link = $this->generateUrl('login.reset', [
			'code' => $code
		]);

		$link = $this->domaine . $link;

		$message = (new \Swift_Message('Réinitialiser le Mot de Passe'))
			->setFrom('robot@lecolonirritable.com')
			->setTo($user->getMail())
			->setBody($this->renderer->render('emails/passwordReset.html.twig', [
				'member' => $user,
				'link' => $link,
			]), 'text/html');

		$this->mailer->send($message);
	}

	private function updateCode(User $user): string
	{
	    $code = $this->hashMomentIt($user->getMail());
        $user->setCode($code);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $code;
	}

	private function hashMomentIt($message): string
	{
		$time = time();
		$newMessage = $message . $time;
		$output = hash($this->hashing_method, $newMessage);
		return $output;
	}
}