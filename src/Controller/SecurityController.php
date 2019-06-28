<?php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\UserRepository;
use App\Form\ForgetPasswordFormType;
use App\Form\ResetPasswordType;
use App\Security\SendAccountCode;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

	public function forgetPassword(UserRepository $userRepository, SendAccountCode $sendAccountCode, Request $request)
	{
		$user = $this->getUser();

		if (!is_null($user))
		{
		    return $this->redirectToRoute('recipe.index');
		}

		$form = $this->createForm(ForgetPasswordFormType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$input = $form->get('username')->getData();
			$user = $userRepository->loadUserByUsername($input);

			if (!is_null($user))
			{
				$sendAccountCode->sendPasswordResetLink($user);
		    	$this->addFlash('success', 'Un mail vient de vous être envoyé. Veuillez cliquer sur son lien pour être redirigé vers la page de modification du mot de passe.');
			}
			else
			{
		    	$this->addFlash('error', 'Ce compte n\'existe pas!');
			}
		}

		return $this->render('security/login.forget.html.twig', [
		    'forgetPasswordForm' => $form->createView(),
		]);
	}

	public function resetPassword(string $code = null, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        if (!is_null($code))
        {
	        $user = $repository->findOneBy(['code' => $code]);

	        if ($user)
	        {
	        	$form = $this->createForm(ResetPasswordType::class, $user);
				$form->handleRequest($request);

				if ($form->isSubmitted() && $form->isValid())
				{
		            $user->setPassword(
		                $passwordEncoder->encodePassword(
		                    $user,
		                    $form->get('plainPassword')->getData()
		                )
		    		);
		    		if(!$user->getIsActive())
		            {
			            $user->setIsActive(true);
			        }
			        $user->setCode('');

		            $entityManager = $this->getDoctrine()->getManager();
		            $entityManager->persist($user);
		            $entityManager->flush();
		        	$this->addFlash('success', 'Votre mot de passe a été modifié avec succès!');
		            return $this->redirectToRoute('recipe.index');
				}
				return $this->render('security/login.reset.html.twig', [
				    'resetPasswordForm' => $form->createView(),
				    'code' => $code
				]);
	        }
        }
        $user = $this->getUser();

		if (is_null($user))
		{
    		$this->addFlash('error', 'Le code de réinitialisation du mot de passe est perimé. Veuillez effectuer une nouvelle demande!');
		}
	    return $this->redirectToRoute('recipe.index');
    }
}