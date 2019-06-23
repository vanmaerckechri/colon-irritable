<?php
namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\TimeType;
use App\Entity\Comment;
use App\Entity\Recette;

use App\Repository\CommentRepository;
use App\Form\CommentType;
// Permet de manipuler les tables de la BDD.
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CrudCommentController extends AbstractController
{
	/**
	 * @var TestRepository
	 */
	private $repository;

	/**
	 * @var ObjectManager
	 */
	private $em;

	public function __construct(CommentRepository $repository, ObjectManager $em)
	{
		$this->repository = $repository;
		$this->em = $em;
	}

	public function create(Recette $recette, string $slug, Request $request)
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
        $user = $this->getUser();
		$post = $request->request->get('comment');


		$comment->setUser($user);
		$comment->setRecette($recette);

		$form = $this->createForm(CommentType::class, $comment);

   		$form->handleRequest($request);

		
		if ($form->isSubmitted() && $form->isValid() && !$this->repository->checkAlreadyComment($user, $recette)) {
        	$this->em->persist($comment);
        	$this->em->flush();
        	return $this->redirectToRoute('recipe.show', [
				'current_menu' => 'recipe.show',
				'id' => $recette->getId(),
				'slug' => $slug
			]);
        }

        return $this->redirectToRoute('recipe.show', [
			'current_menu' => 'recipe.show',
			'id' => $recette->getId(),
			'slug' => $slug
		]);
	}
}