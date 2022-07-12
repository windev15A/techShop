<?php

namespace App\Repository;

use DateTime;
use App\Entity\Promo;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Promo>
 *
 * @method Promo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promo[]    findAll()
 * @method Promo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promo::class);
    }

    public function add(Promo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Promo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Promo[] Returns an array of Promo objects
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

    /**
     * findOneBycode
     *
     * @param  string $code
     * @return Promo
     */
    public function findOneBycode(string $code): ?Promo
    {
        $today = new DateTime("now");
        try {
            return $this->createQueryBuilder('p')
                ->andWhere('p.codePromo = :val')
                ->setParameter('val', $code)
                ->andWhere(':date between p.date_debut AND p.date_fin')
                ->setParameter(':date', $today)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return $th->getMessage();
        }
    }
}
