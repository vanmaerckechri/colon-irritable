<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Recette::class);
    }



    public function findAllWithOptions(?array $afficher, ?string $choiceTri, ?User $user)
    {
        $build = $this->createQueryBuilder('r');

        if ($user)
        {
            if (($afficher['vosRea'] != 'on' && $afficher['vosFav'] != 'on' && $afficher['autres'] != 'on') || ($afficher['vosRea'] == 'on' && $afficher['vosFav'] == 'on' && $afficher['autres'] == 'on'))
            {
                $build->where('r.id != :id')
                    ->setParameter('id', -1);
            }
            else
            {
                $favoris = $user->getFavoris();

                if ($afficher['autres'] == 'on')
                {
                    $build->where('r.id != :id')
                        ->setParameter('id', -1);
                }
                else
                {
                     $build->where('r.id = :id')
                        ->setParameter('id', -1);               
                }

                if ($afficher['vosRea'] == 'on')
                {
                    $build->orWhere('r.user = :user')
                        ->setParameter('user', $user);
                }
                else
                {
                    $build->andWhere('r.user != :user')
                        ->setParameter('user', $user);
                }

                if ($afficher['vosFav'] == 'on')
                {
                    $index = 0;
                    foreach ($favoris as $fav) 
                    {
                        $build->orWhere('r.id = :id' . $index)  
                            ->setParameter('id' . $index, $fav);
                        $index += 1;
                    }
                }
                else
                {
                    $index = 0;
                    foreach ($favoris as $fav) 
                    {
                        $build->andWhere('r.id != :id' . $index)  
                            ->setParameter('id' . $index, $fav);
                        $index += 1;
                    }
                }
            }
        }

        if ($afficher['entrees'] === 'on' || $afficher['platsPrincipaux'] === 'on' || $afficher['desserts'] === 'on')
        {
            $types = [];
            if ($afficher['entrees'] === 'on')
            {
                array_push($types, 0);
            }
            if ($afficher['platsPrincipaux'] === 'on')
            {
                array_push($types, 1);
            }
            if ($afficher['desserts'] === 'on')
            {
                array_push($types, 2);
            }

            $whereContent = '';
            $index = 0;
            foreach ($types as $type)
            {
                if ($index > 0)
                {
                    $whereContent .= ' OR ';
                }
                $whereContent .= 'r.type = ' . $type;
                $index += 1;
            }
            $build->andWhere($whereContent);
        }

        $order = 'r.createdAt';
        $direction = 'DESC';
        if ($choiceTri === 'titre')
        {
            $order = 'r.titre';
            $direction = 'ASC';
        }
        else if ($choiceTri === 'score')
        {
            $order = 'r.avScore';
        }
        $build->orderBy($order, $direction);

        return $build->getQuery()
            ->getResult();
    }
}
