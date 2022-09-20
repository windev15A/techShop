<?php

namespace App\Repository;

use App\Entity\HistoireCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoireCommande>
 *
 * @method HistoireCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoireCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoireCommande[]    findAll()
 * @method HistoireCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoireCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoireCommande::class);
    }

    public function add(HistoireCommande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HistoireCommande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return HistoireCommande[] Returns an array of HistoireCommande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HistoireCommande
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getStatistique()
    {

        $em =  $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(id) AS nbOrder, SUM(montant) AS total
        FROM histoire_commande";

        $stm = $em->prepare($sql);
        $results = $stm->executeQuery();
        return $results->fetchAllAssociative();

        
    }

}
