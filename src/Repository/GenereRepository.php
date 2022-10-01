<?php

namespace App\Repository;

use App\Entity\Genere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Genere>
 *
 * @method Genere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genere[]    findAll()
 * @method Genere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genere::class);
    }

    public function save(Genere $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Genere $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function obtenerVideojuegosGenere()
    {
        return $this->createQueryBuilder("g")
            ->select("g")
            ->innerJoin('g.videojocs', 'v', 'WITH', 'p.phone = :phone')
            ->getQuery()
            ->getResult();
    }
    public function getVideojocs(int $id)
    {
        return $this
            ->createQueryBuilder('g')
            ->andWhere('g.id = :id')
            ->setParameter('id', $id)
            ->innerJoin('g.videojocs', 'videojoc')
        
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Genere[] Returns an array of Genere objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Genere
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
