<?php
namespace App\Controller;

// AbstractController permet d'utiliser des raccourcis pour les services du framework.
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Permet de communiquer avec le serveur... je suppose...
use Symfony\Component\HttpFoundation\Response;
// Twig est utilisé pour afficher les templates html.twig. Dans ce cas précis, pas besoin de l'appeler car j'utilise AbstractController.
// use Twig\Environment;


class RecipeController extends AbstractController
{

	/* Pour l'utilisation d'un service sans passer par AbstractController...

	public function __construct(Environment $twig)
	{
		$this->twig = $twig;
	}

	public function index() : Response
	{
		return new Response($this->twig->render('pages/recette_index.html.twig'));
	}

	*/

	public function index() : Response
	{
		return $this->render('pages/recette_index.html.twig', [
			'current_menu' => 'recipe.index'
		]);
	}

	public function create() : Response
	{
		return $this->render('pages/recette_creer.html.twig', [
			'current_menu' => 'recipe.create'
		]);
	}
}