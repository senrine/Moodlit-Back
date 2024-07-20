<?php

namespace App\Repository;

use App\Entity\DailyJournal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DailyJournal>
 *
 * @method DailyJournal|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailyJournal|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailyJournal[]    findAll()
 * @method DailyJournal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyJournalRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, DailyJournal::class);
        $this->entityManager = $entityManager;
    }

    public function save(DailyJournal $journal): void
    {
        $this->entityManager->persist($journal);
        $this->entityManager->flush();
    }

    public function remove(DailyJournal $journal): void
    {
        $this->entityManager->remove($journal);
        $this->entityManager->flush();
    }

    public function getWeekJournal(\DateTimeInterface $currentDate, int $editor_id): array
    {
        $endDate = clone $currentDate;
        $startDate = (clone $currentDate)->modify('-6 days');

        return $this->createQueryBuilder('d')
            ->andWhere('d.editor = :editor_id')
            ->andWhere('d.created_at >= :startDate')
            ->andWhere('d.created_at <= :endDate')
            ->setParameter('editor_id', $editor_id)
            ->setParameter('startDate', $startDate->format('Y-m-d 00:00:00'))
            ->setParameter('endDate', $endDate->format('Y-m-d 23:59:59'))
            ->orderBy('d.created_at', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getDailyJournalsByDate(\DateTimeInterface $date, int $editor_id): DailyJournal | null
    {
        $startDate = clone $date;
        $endDate = clone $date;
        $endDate->modify('+1 day');
        return $this->createQueryBuilder('d')
            ->andWhere('d.created_at >= :startDate')
            ->andWhere('d.created_at < :endDate')
            ->andWhere('d.editor = :editor_id')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('editor_id', $editor_id)
            ->orderBy('d.created_at', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
//    /**
//     * @return DailyJournal[] Returns an array of DailyJournal objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DailyJournal
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
