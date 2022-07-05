<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Filter;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{

    protected $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Product::class);
        $this->logger = $logger;

    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }



    /**
     * recherche produit 
     *
     * @param  Filter $filter
     * @return Product[]
     */
    public function recherche(Filter $filter) :array
    {

        try {
            
            $query = $this->createQueryBuilder('p')
                ->select("p", "c", "f")
                ->join("p.category", "c")
                ->join("p.fabricant", "f");

            if ($filter->q) {
                $query = $query
                    ->andWhere("p.libelle LIKE :q")
                    ->orWhere("p.prix LIKE :q")
                    ->orWhere("p.description LIKE :q")
                    ->orWhere("c.libelle LIKE :q")
                    ->orWhere("f.nom LIKE :q")
                    ->setParameter("q", "%{$filter->q}%");
            }

            if ($filter->categories) {
                $query = $query
                    ->andWhere("c.id IN (:categories)")
                    ->setParameter('categories', $filter->categories);
            }
            if ($filter->fabricants) {
                $query = $query
                    ->andWhere("c.id IN (:fabricants)")
                    ->setParameter('fabricants', $filter->fabricants);
            }

            if ($filter->min) {
                $query = $query
                    ->andWhere("p.prix >= :min")
                    ->setParameter('min', $filter->min);
            }
            if ($filter->max) {
                $query = $query
                    ->andWhere("p.prix <= :max")
                    ->setParameter('max', $filter->max);
            }
            if ($filter->order) {
                switch ($filter->order) {
                    case 1:
                        $query = $query
                            ->orderBy("p.prix", "ASC");
                        break;
                    case 2:
                        $query = $query
                            ->orderBy("p.prix", "DESC");
                        break;
                    case 3:
                        $query = $query
                            ->orderBy("p.libelle", "ASC");
                        break;
                    case 4:
                        $query = $query
                            ->orderBy("p.libelle", "DESC");
                        break;
                }
            }
            return $query
                ->getQuery()
                ->getResult();
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return [];
        }
    }
}
