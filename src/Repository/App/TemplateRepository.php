<?php

namespace App\Repository\App;

use App\Entity\App\Template;
use Doctrine\Common\Collections\Criteria;
use Uloc\ApiBundle\Repository\BaseRepositoryService;
use Uloc\ApiBundle\Repository\SortableInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Template|null find($id, $lockMode = null, $lockVersion = null)
 * @method Template|null findOneBy(array $criteria, array $orderBy = null)
 * @method Template[]    findAll()
 * @method Template[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateRepository extends BaseRepositoryService implements SortableInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Template::class);
    }

    public function findAllSimple(int $limit = 100, int $offset = 0, $sortBy = null, $sortDesc = null, array $filters = null, $active = true, $hideDeleted = true)
    {
        $sortByPossibles = [
            'id' => 'a.id',
            'active' => 'a.active',
            'nome' => 'c.nome'
        ];

        return $this->findAllSimpleBasic(
            Template::class,
            $sortByPossibles,
            $limit,
            $offset,
            $sortBy,
            $sortDesc,
            $filters,
            $active,
            $hideDeleted,
            null,
            \Doctrine\ORM\Query::HYDRATE_ARRAY,
            'a, c',
            function (QueryBuilder $query) {
                $query->leftJoin('a.categoria', 'c');
            }
        );
    }

    // /**
    //  * @return Template[] Returns an array of Template objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Template
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
