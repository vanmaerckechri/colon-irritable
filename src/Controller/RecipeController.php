<?php
namespace App\Controller;

// Appeler la table Test.
use App\Entity\Recette;
use App\Entity\Comment;
// Permet d'accéder à une table. Sert uniquement si on appelle la table via le construct du controller ou par les arguments de classe.
// Si la table avait directement été appelée dans une classe ex: $repository = $this->getDoctrine()->getRepository(Nomdelatable::class); Il aurait été inutile de l'appeler ici.
use App\Repository\UserRepository;
use App\Repository\RecetteRepository;
use App\Repository\CommentRepository;

// AbstractController permet d'utiliser des raccourcis pour les services du framework.
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Permet de communiquer avec le serveur... je suppose...
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
// Twig est utilisé pour afficher les templates html.twig. Dans ce cas précis, pas besoin de l'appeler car j'utilise AbstractController.
// use Twig\Environment;
use App\Form\RecetteType;
use App\Form\CommentType;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;

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

	/**
	 * @var ObjectManager
	 */
	private $em;

	public function __construct(UserRepository $userRepository, RecetteRepository $repository, CommentRepository $commentRepository)
	{
		$this->userRepository = $userRepository;
		$this->repository = $repository;
		$this->commentRepository = $commentRepository;
	}

	public function index(PaginatorInterface $paginator, Request $request) : Response
	{
		$user = $this->getUser();

		$trier = $request->request->get('trier');

		$afficher = array(
			'vosFav' => $request->request->get('vosFav'),
			'vosRea' => $request->request->get('vosRea'),
			'autres' => $request->request->get('autres'),
			'entrees' => $request->request->get('entrees'),
			'platsPrincipaux' => $request->request->get('platsPrincipaux'),
			'desserts' => $request->request->get('desserts'),
		);

		$recettes = $paginator->paginate(
			$this->repository->findAllWithOptions($afficher, $trier, $user),
			$request->query->getInt('page', 1),
			12
		);

		return $this->render('recipe/index.html.twig', [
			'current_menu' => 'recipe.index',
			'recettes' => $recettes,
			'trier' => $trier,
			'afficher' => $afficher
		]);
	}

	public function show(Recette $recette, string $slug, Request $request) : Response
	{
		$goodSlug = $recette->getSlug();

		if ($goodSlug !== $slug)
		{
			return $this->redirectToRoute('recipe.show', [
					'id' => $recette->getId(),
					'slug' => $goodSlug
				], 301);
		}

		$comment = new Comment;

		$form = $this->createForm(CommentType::class, $comment, [
				'action' => $this->generateUrl('comment.create', [
				'id' => $recette->getId(),
				'slug' => $slug,
			]),
		]);


		$userInfos = $this->getUserInfos($recette);
		$isFav = $userInfos[0];
		$userAlreadyComment = $userInfos[1];

		$recetteAverageScore = $recette->getAvScore();

		$author = $this->userRepository->findOnNameBy($recette->getUser());

       	return $this->render('recipe/show.html.twig', [
			'current_menu' => 'recipe.show',
			'recette' => $recette,
			'userAlreadyComment' => $userAlreadyComment,
			'recetteAverageScore' => $recetteAverageScore,
			'form' => $form->createView(),
			'isFav' => $isFav,
			'author' => $author
		]);
	}

	public function getUserInfos(Recette $recette) : array
	{
		$user = $this->getUser();
        $isFav = false;
        $userAlreadyComment = false;
        if ($user)
        {
	        $isFav = $this->userRepository->isUserFanRecette($user->getId(), $recette->getId());

			$userAlreadyComment = false;
			if ($this->commentRepository->checkAlreadyComment($user, $recette))
			{
				$userAlreadyComment = true;
			}
        }
        return [$isFav, $userAlreadyComment];
	}
}