<?php

namespace App\Repository;

use App\Entity\AutresAuteurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AutresAuteurs>
 *
 * @method AutresAuteurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method AutresAuteurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method AutresAuteurs[]    findAll()
 * @method AutresAuteurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutresAuteursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AutresAuteurs::class);
    }

    public function save(AutresAuteurs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AutresAuteurs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AutresAuteurs[] Returns an array of AutresAuteurs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AutresAuteurs
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
