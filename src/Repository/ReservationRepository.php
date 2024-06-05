<?php

namespace App\Repository;

use App\Entity\Materiel;
use App\Entity\Reservation;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }
    // public function findByMaterielAndDate(Materiel $materiel, \DateTimeInterface $date_debut, \DateTimeInterface $date_fin): array
    // {
    //     $date_debut_formatted = $date_debut->format('Y-m-d');
    //     $date_fin_formatted = $date_fin->format('Y-m-d');
    
    //     $queryBuilder = $this->createQueryBuilder('r')
    //         ->andWhere('r.materiel = :materiel')
    //         ->andWhere('r.date_fin >= :date_debut')
    //         ->andWhere('r.date_debut <= :date_fin')
    //         ->setParameter('materiel', $materiel)
    //         ->setParameter('date_debut', $date_debut_formatted)
    //         ->setParameter('date_fin', $date_fin_formatted);
    
    //     return $queryBuilder->getQuery()->getResult();
    // }
    public function findByMaterielAndDate(Materiel $materiel, \DateTimeInterface $date_debut, \DateTimeInterface $date_fin): array
{
    $date_debut_formatted = $date_debut->format('Y-m-d');
    $date_fin_formatted = $date_fin->format('Y-m-d');

    $queryBuilder = $this->createQueryBuilder('r')
        ->andWhere('r.materiel = :materiel')
        ->andWhere('r.date_fin >= :date_debut')
        ->andWhere('r.date_debut <= :date_fin')
        ->andWhere('(r.valide IS NULL OR r.valide = true)')
        ->setParameter('materiel', $materiel)
        ->setParameter('date_debut', $date_debut_formatted)
        ->setParameter('date_fin', $date_fin_formatted);

    return $queryBuilder->getQuery()->getResult();
}
public function findByMaterielNonRetourne(Materiel $materiel, \DateTimeInterface $date): array
{
    $dateFormatted = $date->format('Y-m-d');

    $queryBuilder = $this->createQueryBuilder('r')
        ->andWhere('r.materiel = :materiel')
        ->andWhere('r.date_fin < :date')
        ->andWhere('r.retourne = 0')
        ->setParameter('materiel', $materiel)
        ->setParameter('date', $dateFormatted);

    return $queryBuilder->getQuery()->getResult();
}


    public function findOneByDateAndMateriel($date_debut, $date_fin, $id, $materiel)
    {
        $date_debut_formatted = $date_debut->format('Y-m-d');
        $date_fin_formatted = $date_fin->format('Y-m-d');
    
        return $this->createQueryBuilder('r')
            ->andWhere('r.date_debut >= :date_debut')
            ->andWhere('r.date_fin <= :date_fin')
            ->andWhere('r.id != :id')
            ->join('r.materiel', 'm')
            ->andWhere('m.id = :materiel')
            ->setParameter('materiel', $materiel)
            ->setParameter('date_debut', $date_debut_formatted)
            ->setParameter('date_fin', $date_fin_formatted)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function findOneByMateriel($materiel)
    {
        return $this->createQueryBuilder('r')
            ->join('r.materiel', 'm')
            ->andWhere('m.id = :materiel')
            ->setParameter('materiel', $materiel)
            ->getQuery()
            ->getResult();
    }
    

    public function save(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
