<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function save(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Undocumented function
     *
     * @return Category []
     */
    public function findAllOrdered(): array{

        return $this->createQueryBuilder('category')
        ->orderBy('category.name', 'DESC')
        ->getQuery()
        ->getResult();

    }

    /**
     * Undocumented function
     *
     * @return Category []
     */
    public function search(string $term): array{

        $qb = $this->addOrderByCategoryName();

        return $this->addFortuneCookieJoinAndSelect($qb)
        ->andWhere('category.name LIKE :term OR category.iconKey LIKE :term OR fortuneCookie.fortune LIKE :term')
        ->setParameter('term', '%'.$term.'%')
        ->orderBy('category.name', Criteria::DESC)
        ->getQuery()
        ->getResult();

        // ->addSelect('fortuneCookie')
        // ->leftJoin('category.fortuneCookies', 'fortuneCookie')
        // ->andWhere('category.name LIKE :term OR category.iconKey LIKE :term OR fortuneCookie.fortune LIKE :term')
        // ->setParameter('term', '%'.$term.'%')
        // ->orderBy('category.name', Criteria::DESC)
        // ->getQuery()
        // ->getResult();

    
        /*Method without leftjoin*/
        // return $this->createQueryBuilder('category')
        // ->andWhere('category.name LIKE :term OR category.iconKey LIKE :term')
        // ->setParameter('term', '%'.$term.'%')
        // ->addOrderBy('category.name', Criteria::DESC)
        // ->getQuery()
        // ->getResult();
    }




    /**
     * Consulta mediante DQL
     *
     * @return Category []
     */
    public function findAllOrderedDQL(): array{ 
        
        $dql = 'SELECT category FROM App\Entity\Category as category ORDER BY category.name DESC';

        $query = $this->getEntityManager()->createQuery($dql)->getResult();
        return $query;


    }

    /**
     * Consuta que reduce la doble query para una categoria a una unica query
     *
     * @param integer $id
     * @return Category
     */
    public function findWithFortunesJoin(int $id): ?Category{
        $qb = $this->createQueryBuilder('category');

        return $this->addFortuneCookieJoinAndSelect($qb)
        //->leftJoin('category.fortuneCookies', 'fortuneCookie')
        ->andWhere('category.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult();
    
    }

    public function addFortuneCookieJoinAndSelect(QueryBuilder $qb = null) {     
        return ($qb ?? $this->createQueryBuilder('category'))
        ->addSelect('fortuneCookie')
        ->leftJoin('category.fortuneCookies', 'fortuneCookie')
        ;  
    }

    public function addOrderByCategoryName(QueryBuilder $qb = null) :QueryBuilder{
        return ($qb ?? $this->createQueryBuilder('category'))
        ->orderBy('category.name', Criteria::DESC);
    }

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
