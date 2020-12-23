<?php

namespace App\Repository\App;

use App\Entity\App\Variavel;
use Uloc\ApiBundle\Repository\BaseRepositoryService;
use Uloc\ApiBundle\Repository\SortableInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Variavel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Variavel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Variavel[]    findAll()
 * @method Variavel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariavelRepository extends BaseRepositoryService implements SortableInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->setFieldSearch('name');
        $this->setAdditionalSortByPossibles([
            'nome' => 'a.name'
        ]);
        parent::__construct($registry, Variavel::class);
    }

    // /**
    //  * @return Variavel[] Returns an array of Variavel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Variavel
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
