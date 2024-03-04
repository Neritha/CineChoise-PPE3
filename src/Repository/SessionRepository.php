<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\ORM\Query;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function listeSeancesComplete () : ?Query
    {
        return $this->createQueryBuilder('s')
            ->select('s')#, 'sa')// selection des tables | s seance |sa salle
            #->leftJoin('s.salle', 'sa')
            ->orderBy('s.id')
            ->getQuery(); 
    }

    
    public function listeSeancesCompleteAdmin () : ?Query
    {
        return $this->createQueryBuilder('s')
            ->select('s')#, 'sa')// selection des tables | s seance |sa salle
            #->leftJoin('s.salle', 'sa')
            ->orderBy('s.date')
            ->getQuery(); 
    }


//    /**
//     * @return Session[] Returns an array of Session objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
