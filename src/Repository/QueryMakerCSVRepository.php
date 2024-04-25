<?php

namespace App\Repository;

use App\Entity\QueryMakerCSV;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QueryMakerCSV>
 *
 * @method QueryMakerCSV|null find($id, $lockMode = null, $lockVersion = null)
 * @method QueryMakerCSV|null findOneBy(array $criteria, array $orderBy = null)
 * @method QueryMakerCSV[]    findAll()
 * @method QueryMakerCSV[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QueryMakerCSVRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QueryMakerCSV::class);
    }

//    /**
//     * @return QueryMakerCSV[] Returns an array of QueryMakerCSV objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QueryMakerCSV
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
