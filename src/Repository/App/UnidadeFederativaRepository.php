<?php

namespace App\Repository\App;

use App\Entity\App\UnidadeFederativa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UnidadeFederativa|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnidadeFederativa|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnidadeFederativa[]    findAll()
 * @method UnidadeFederativa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnidadeFederativaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnidadeFederativa::class);
    }

    // /**
    //  * @return UnidadeFederativa[] Returns an array of UnidadeFederativa objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UnidadeFederativa
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
