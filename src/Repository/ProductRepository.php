<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // Récupérer les produits par catégorie
    public function findByCategory($categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $categoryId)
            ->getQuery()
            ->getResult();
    }

    // ✅ Recherche par nom
    public function searchByName(string $query): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.name LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // ✅ Produits les plus récents
    public function findLatest(int $limit = 6): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    // ✅ Compter le nombre total de produits
    public function countAll(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // ✅ KAN-4 : recherche combinée nom + catégorie (les deux paramètres sont optionnels)
    public function search(?string $name, ?int $categoryId): array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC');

        if ($name !== null && $name !== '') {
            $qb->andWhere('p.name LIKE :name')
               ->setParameter('name', '%' . $name . '%');
        }

        if ($categoryId !== null) {
            $qb->join('p.category', 'c')
               ->andWhere('c.id = :categoryId')
               ->setParameter('categoryId', $categoryId);
        }

        return $qb->getQuery()->getResult();
    }
}