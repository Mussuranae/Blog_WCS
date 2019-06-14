<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findAllWithCategories()
    {
        $qb = $this->createQueryBuilder('a')
            ->innerJoin('a.category', 'c' )
            ->addSelect('c')
            ->getQuery();

        return $qb->execute();
    }

    public function findAllWithCategoriesAndTags()
    {
        $queryBuilder = $this->createQueryBuilder('a')
                        ->leftJoin('a.tags', 't')
                        ->leftJoin('a.category', 'c')
                        ->addSelect('t')
                        ->addSelect('c')
                        ->getQuery();

        return $queryBuilder->execute();
    }

}
