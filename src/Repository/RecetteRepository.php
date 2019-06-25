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
        $where = 'Where';

        if ($user)
        {
            $favoris = $user->getFavoris();
            if ($afficher['vosRea'] != 'on' || $afficher['vosFav'] != 'on' || $afficher['autres'] != 'on')
            {
                if ($afficher['autres'] == 'on')
                {
                    if ($afficher['vosRea'] != 'on')
                    {
                        $build->$where('r.user != :user')
                            ->setParameter('user', $user);
                        $where = 'andWhere';
                    }
                    if ($afficher['vosFav'] != 'on')
                    {
                        $index = 0;
                        foreach ($favoris as $fav) 
                        {
                            $build->$where('r.id != :id' . $index)  
                                ->setParameter('id' . $index, $fav);
                            $where = 'andWhere';
                            $index += 1;
                        }
                    }
                    $where = 'orWhere';
                }
                if ($afficher['vosRea'] == 'on')
                {
                    $build->$where('r.user = :user')
                        ->setParameter('user', $user);
                    $where = 'orWhere';
                }
                if ($afficher['vosFav'] == 'on')
                {
                    $index = 0;
                    foreach ($favoris as $fav) 
                    {
                        $build->$where('r.id = :id' . $index)  
                            ->setParameter('id' . $index, $fav);
                        $where = 'orWhere';
                        $index += 1;
                    }
                }
            }
            $where = 'andWhere';
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
            $build->$where($whereContent);
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
