<?php

namespace App\Repository\App;

use Uloc\ApiBundle\Entity\App\GlobalConfig;
use Uloc\ApiBundle\Repository\BaseRepositoryService;
use Uloc\ApiBundle\Repository\SortableInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GlobalConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method GlobalConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method GlobalConfig[]    findAll()
 * @method GlobalConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GlobalConfigRepository extends BaseRepositoryService implements SortableInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->setFieldSearch('name');
        $this->setAdditionalSortByPossibles([
            'nome' => 'a.name'
        ]);
        parent::__construct($registry, GlobalConfig::class);
    }

    public function findPublics($list) {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('c')
            ->from(GlobalConfig::class)
            ->where('c.name IN :names')
            ->andWhere('c.extra LIKE :public')
            ->setParameter('names', $list)
            ->setParameter('public', "%{public: true}%");

        return $query->getQuery()->getArrayResult();
    }
}
