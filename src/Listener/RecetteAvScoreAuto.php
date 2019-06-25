<?php
namespace App\Listener;

use App\Entity\Recette;
use App\Entity\Comment;
use Doctrine\Common\EventSubscriber;
// for Doctrine < 2.4: use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteAvScoreAuto extends AbstractController implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove
        ];
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Comment)
        {
            return;
        }
        $this->calculRecetteAverageScore($entity);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Comment)
        {
            return;
        }
        $this->calculRecetteAverageScore($entity);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Comment)
        {
            return;
        }
        $this->calculRecetteAverageScore($entity);
    }

    public function calculRecetteAverageScore(Comment $comment) : void
    {
        $em = $this->getDoctrine()->getManager();
        $recette = $comment->getRecette();
        $comments = $recette->getComments();
        $output = 0;
        $commentLength = count($comments);
        if ($commentLength > 0)
        {
            foreach ($comments as $comment) 
            {
                $output += $comment->getScore();
            }
            $output = round($output / $commentLength, 1);
        }
        $recette->setAvScore($output);
        $em->flush();
    }
}
