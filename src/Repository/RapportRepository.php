<?php

namespace App\Repository;

use App\Entity\Rapport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rapport>
 *
 * @method Rapport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rapport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rapport[]    findAll()
 * @method Rapport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapportRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, Rapport::class);
    }

    public function save(Rapport $rapport) : void
    {
        $this->entityManager->persist($rapport);
        $this->entityManager->flush();
    }

    public function remove(Rapport $rapport) : void
    {
        $this->entityManager->remove($rapport);
        $this->entityManager->flush();
    }

//    /**
//     * @return Rapport[] Returns an array of Rapport objects
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

//    public function findOneBySomeField($value): ?Rapport
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
