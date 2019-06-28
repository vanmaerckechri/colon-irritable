<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\SendAccountCode;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, SendAccountCode $sendAccountCode): Response
    {
        $user = $this->getUser();

        if (!is_null($user))
        {
            return $this->redirectToRoute('recipe.index');
        }

        $user = new User();
        $user->setPassword('turlututu_2.0');
        $user->setIsActive(false);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $sendAccountCode->sendActivationLink($user);

            $this->addFlash('success', 'Un mail vient de vous être envoyé. Veuillez cliquer sur son lien d\'activation pour pouvoir accéder à votre compte.');
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function activation(string $code, Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        $user = $repository->findOneBy(['code' => $code]);

        if ($user)
        {
            if(!$user->getIsActive())
            {
                $user->setIsActive(true);
                $user->setCode('');

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                
                $this->addFlash('success', 'Votre compte vient d\'être activé avec succès.');
            }
        }
        else
        {
            $this->addFlash('error', 'Le code d\'activation est perimé. Veuillez tenter de vous connecter à votre compte pour vérifier s\'il n\'est pas déjà activé. Dans le cas contraire, vous recevrez un nouveau code d\'activation dans votre boîte mail');
        }
        return $this->redirectToRoute('recipe.index');
    }
}