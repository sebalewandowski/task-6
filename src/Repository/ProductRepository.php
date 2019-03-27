<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function getProductsTotalAvailability()
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.availability)')
            ->andWhere('p.availability = :val')
            ->setParameter('val', true)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns an array of unavialble Product objects
     */
    public function findOneBySomeField()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.availability = :val')
            ->setParameter('val', 0)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns an array of Product objects by passed phrase
     */
    public function findByPhrase($word)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :name')
            ->setParameter('name', '%'.$word.'%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
