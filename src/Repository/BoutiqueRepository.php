<?php

namespace App\Repository;

use App\Entity\Boutique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Boutique>
 *
 * @method Boutique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Boutique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Boutique[]    findAll()
 * @method Boutique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoutiqueRepository extends ServiceEntityRepository
{

    protected $logger;


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Boutique::class);
    }

    public function add(Boutique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Boutique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Boutique[] Returns an array of Boutique objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Boutique
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    
    /**
     * findByName
     *
     * @param  string $name
     * @return Boutique
     */
    public function findByName(string $name)
    {
        return $this->createQueryBuilder('b')
            ->where('b.nom LIKE :nom')
            ->setParameter('nom', '%' . $name . '%')
            ->orderBy('b.nom', 'asc')
            ->getQuery()
            ->getResult();
    }

    public function findProxyBoutique(Boutique $boutique, string $distance)
    {
        $conn = $this->getEntityManager()->getConnection();

        // Requete SQL pour recupérer tous boutiques avec une distance inférieur de la distance choisi par l'utilisateur
        $sql = "SELECT id, nom,adresse, latitude, longitude, ( 6371 * acos( cos( radians(:lattitude) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(:longitude) ) + sin( radians(:lattitude1) ) * sin( radians( latitude ) ) ) ) AS distance FROM `boutique` HAVING distance < :distance ORDER BY distance";
        
        try {
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery([
                'lattitude' =>  $boutique->getLatitude(),
                'longitude' => $boutique->getLongitude(),
                'distance' => (int)$distance,
                'lattitude1' => $boutique->getLatitude(),
            ]);    
            return $resultSet->fetchAllAssociative();
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
}
