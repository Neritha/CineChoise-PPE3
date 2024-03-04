<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\ORM\Query;
use App\Model\FiltreFilmAdmin;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

   public function listeFilmsComplete() : ?Query
   {
       return $this->createQueryBuilder('f')
           ->select('f', 'c', 's')
           ->leftJoin('f.categories', 'c')
           ->leftJoin('f.sessions', 's')
           ->orderBy('f.nom', 'ASC')
           ->getQuery()
       ;
   }

   public function listeFilmsCompleteAdmin(FiltreFilmAdmin $filtre = null) : ?Query
   {
       $query = $this->createQueryBuilder('f')
           ->select('f', 'c', 's')
           ->leftJoin('f.categories', 'c')
           ->leftJoin('f.sessions', 's')
           ->orderBy('f.nom', 'ASC');
           if(!empty($filtre->nom)){
                $query->andWhere('f.nom like :nomChercher')
                ->setParameter('nomChercher', "%{$filtre->nom}%");
        }
           if(!empty($filtre->category)){
            $query->andWhere(':categoryChercher MEMBER OF f.categories')
            ->setParameter('categoryChercher', $filtre->category);
        }
       ;
       return $query->getQuery();
   }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
