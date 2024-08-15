<?php

namespace App\Repository;

use App\Entity\MicroPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MicroPost>
 */
class MicroPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MicroPost::class);
    }


    public function findAllWithComments(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.comments', 'c') // `leftJoin` yerine `join` kullanıyorsunuz, bu da yalnızca `p.comments` ilişkisi olan `MicroPost`ları getirecektir
            ->addSelect('c')
            ->orderBy('p.created', 'DESC')
            ->getQuery()
            ->getResult();
    }

    private function findAllQuery(
        bool $withComments = false,
        bool $withLikes = false,
        bool $withAuthors = false,
        bool $withProfiles = false,
    ) : QueryBuilder{
        $query = $this->createQueryBuilder('p');

        if($withComments){
            $query->leftJoin('p.comments', 'c')
                ->addSelect('c');
        }

        if($withLikes){
            $query->leftJoin('p.likes', 'l')
                ->addSelect('l');
        }

        if($withAuthors || $withProfiles){
            $query->leftJoin('p.authors', 'a')
                ->addSelect('a');
        }

        if ($withProfiles) {
            $query->leftJoin('a.userProfile', 'up')
                ->addSelect('up');
        }

        return $query->orderBy('p.created', 'DESC');
    }
    

    //    /**
    //     * @return MicroPost[] Returns an array of MicroPost objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MicroPost
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
