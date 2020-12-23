<?php

namespace App\Repository\App;

use App\Entity\App\TemplateCategoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TemplateCategoria|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateCategoria|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateCategoria[]    findAll()
 * @method TemplateCategoria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateCategoriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateCategoria::class);
    }

    // /**
    //  * @return TemplateCategoria[] Returns an array of TemplateCategoria objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TemplateCategoria
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
