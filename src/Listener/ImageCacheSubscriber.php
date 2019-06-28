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
			'preUpdate',
			'prePersist'
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

		if ($entity->getImageFile() instanceof UploadedFile)
		{
			$this->resiveOriginalImage($entity);
			$this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
		}
	}

	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if (!$entity instanceof Recette)
		{
			return;
		}

		if ($entity->getImageFile() instanceof UploadedFile)
		{
			$this->resiveOriginalImage($entity);
			$this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
		}
	}

	private function resiveOriginalImage($entity)
	{
		$src = $entity->getImageFile()->getPathName();
        list($width, $height) = getimagesize($src);
		$newSrc = imagecreatefromjpeg($src);
        $dst = imagecreatetruecolor(800, 600);
        imagecopyresampled($dst, $newSrc, 0, 0, 0, 0, 800, 600, $width, $height);
		imagejpeg($dst, $src);
	}
}