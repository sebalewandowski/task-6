<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param $productId
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByIdJoinedToCategory($name)
    {
        return $this->createQueryBuilder()
            ->select('c.name AS category, p.name AS product, count(p.availability)')
            ->from('Product', 'p')
            ->leftJoin('Category', 'c', Join::WITH, 'p.category = c.id')
            ->andWhere('p.availability = :val')
            ->setParameter('val', true)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns an array of unavialble Product objects
     */
    public function findOneBySomeField()
    {
        return $this->createQueryBuilder()
            ->select('c.name AS category, p.name AS product')
            ->from('Product', 'p')
            ->leftJoin('Category', 'c', Join::WITH, 'p.category = c.id')
            ->orderBy('category', 'DESC')
            ->addOrderBy('product', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
