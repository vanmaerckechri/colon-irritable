<?php
namespace App\Listener;

use App\Entity\Recette;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageCacheSubscriber implements EventSubscriber
{

	/**
	 * @var CacheManager
	 *
	 */
	private $cacheManager;

	/**
	 * @var UploaderHelper
	 */
	private $uploaderHelper;

	public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper)
	{
		$this->cacheManager = $cacheManager;
		$this->uploaderHelper = $uploaderHelper;

	}

	public function getSubscribedEvents()
	{
		return [
			'preRemove',
			'preUpdate'
		];
	}

	public function preRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		if (!$entity instanceof Recette)
		{
			return;
		}
		if ($entity->getImage() !== null)
		{
			$this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
		}
	}

	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getEntity();

		if (!$entity instanceof Recette)
		{
			return;
		}
		$entity->setUpdatedAt(new \DateTime('now'));
		if ($entity->getImageFile() instanceof UploadedFile && $entity->getImage() !== null)
		{
			$this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
		}
	}
}