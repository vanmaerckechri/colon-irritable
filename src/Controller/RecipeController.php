<?php
namespace App\Controller;

// Appeler la table Test.
use App\Entity\Recette;
// Permet d'accéder à une table. Sert uniquement si on appelle la table via le construct du controller ou par les arguments de classe.
// Si la table avait directement été appelée dans une classe ex: $repository = $this->getDoctrine()->getRepository(Nomdelatable::class); Il aurait été inutile de l'appeler ici.
use App\Repository\RecetteRepository;
// AbstractController permet d'utiliser des raccourcis pour les services du framework.
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Permet de communiquer avec le serveur... je suppose...
use Symfony\Component\HttpFoundation\Response;
// Twig est utilisé pour afficher les templates html.twig. Dans ce cas précis, pas besoin de l'appeler car j'utilise AbstractController.
// use Twig\Environment;
use App\Form\RecetteType;

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

	/**
	 * @var TestRepository
	 */
	private $repository;

	public function __construct(RecetteRepository $repository)
	{
		$this->repository = $repository;
	}

	public function index() : Response
	{
		$recettes = $this->repository->findAll();

		return $this->render('recipe/index.html.twig', [
			'current_menu' => 'recipe.index',
			'recettes' => $recettes
		]);
	}

	public function show(Recette $recette, string $slug) : Response
	{
		// Pratique un find id automatique sur $recette car l'id est est présent dans le path. Une autre méthode est de passer l'id dans l'argument de la classe et ensuite effectuer un find.

		// Vérifier que le slug est correct, si ça n'est pas le cas, redirection permanente (301).
		$goodSlug = $recette->getSlug();
		if ($goodSlug !== $slug)
		{
			return $this->redirectToRoute('recipe.show', [
					'id' => $recette->getId(),
					'slug' => $goodSlug
				], 301);
		}

		return $this->render('recipe/show.html.twig', [
			'current_menu' => 'recipe.show',
			'recette' => $recette
		]);
	}
}