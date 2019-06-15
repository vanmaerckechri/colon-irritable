<?php
namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\TimeType;
use App\Entity\Recette;
use App\Repository\RecetteRepository;
use App\Form\RecetteType;
// Permet de manipuler les tables de la BDD.
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CrudRecipeController extends AbstractController
{
	/**
	 * @var TestRepository
	 */
	private $repository;

	/**
	 * @var ObjectManager
	 */
	private $em;

	public function __construct(RecetteRepository $repository, ObjectManager $em)
	{
		$this->repository = $repository;
		$this->em = $em;
	}

	public function create(Request $request)
	{
		$recette = new Recette;

		$form = $this->createForm(RecetteType::class, $recette);
  		$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$this->em->persist($recette);
        	$this->em->flush();
        	$this->addFlash('success', 'Recette crée avec succès!');
        	return $this->redirectToRoute('recipe.index');
        }

		return $this->render('recipe/create.html.twig', [
			'current_menu' => 'recipe.create',
			'recette' => $recette,
			'form' => $form->createView()
		]);
	}

	public function edit(Recette $recette, Request $request)
	{
		$form = $this->createForm(RecetteType::class, $recette);
  		$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$this->em->flush();
        	$this->addFlash('success', 'Recette modifiée avec succès!');
        	return $this->redirectToRoute('recipe.index');
        }

		return $this->render('recipe/edit.html.twig', [
			'recette' => $recette,
			'form' => $form->createView()
		]);
	}

	public function delete(Recette $recette, Request $request)
	{
		if ($this->isCsrfTokenValid('delete' . $recette->getId(), $request->get('_token')))
		{
			$this->em->remove($recette);
			$this->em->flush();
			$this->addFlash('success', 'Recette effacée avec succès!');
		}
        
        return $this->redirectToRoute('recipe.index');
	}
}