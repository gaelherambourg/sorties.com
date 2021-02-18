<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }


    public function trouverToutesSorties(SearchData $search, $id, $date)
    {

        //methode renvoyant l'ensemble des sorties presentes en bdd
        $qb = $this ->createQueryBuilder('s');
        $qb ->join('s.etats_no_etat', 'e')
            ->addSelect('e');
        $qb ->join('s.organisateur', 'o')
            ->addSelect('o');
        $qb ->innerJoin('o.campus_no_campus', 'c')
            ->addSelect('c');
        $qb ->leftJoin('s.participants', 'ps')
            ->addSelect('ps');

        //seulement les sorties datant de moins d'un mois
        $qb = $qb
             ->andWhere(' :dateActuelle < s.datedebut')
             ->setParameter('dateActuelle',new \DateTime('-1 month'));

        //ajout des filtres existants

        dump($search->getCampus());
        dump($id);



        if(!empty($search->getRecherche())){
                $qb = $qb
                    ->andWhere('s.nom LIKE :recherche')
                    ->setParameter('recherche', "%{$search->getRecherche()}%");
            }

        if(!empty($search->getCampus())){
            if($search->getCampus()==0){

            }else{
            $qb = $qb
                ->andWhere('o.campus_no_campus = :campus')
                ->setParameter('campus', "{$search->getCampus()}");
            }
        }

        if($search->isOrganisateur()){
            $qb = $qb
                ->andWhere('s.organisateur = :organisateur')
                ->setParameter('organisateur', "{$id}");
        }

        if($search->isInscrit()){
            $qb = $qb
                ->andWhere('ps.id = :participant')
                ->setParameter('participant', "{$id}");
        }

        if($search->isPasInscrit()){

            $qb = $qb
                ->andWhere(':participant NOT MEMBER OF s.participants')
                ->setParameter('participant', "{$id}");
        }

        if($search->isPassees()){
            $qb = $qb
                ->andWhere('s.datedebut < :date')
                ->setParameter('date', $date);
        }

        if(!empty($search->getDatedeb()) && !empty($search->getDatefin())){
            $qb = $qb
                ->andWhere('s.datedebut BETWEEN :debut AND :fin')
                ->setParameter('debut', $search->getDatedeb())
                ->setParameter('fin', $search->getDatefin());
        }

        $qb = $qb
            ->addOrderBy('s.etats_no_etat', 'ASC')
            ->addOrderBy('s.datedebut', 'ASC');


        $query = $qb->getQuery();
        return $query->getResult();

    }


    public function filtrerSorties()
    {


    }



    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
//    public function update(?object $sortie)
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->execute()
//            ;
//    }
    public function rechercherSortieParIdAvecLieuEtVilleAssocie($idSortie)
    {
        return $this->createQueryBuilder('s')
            ->join('s.lieux_no_lieu', 'l')
            ->addSelect('l')
            ->join('l.villes_no_ville', 'v')
            ->addSelect('v')
            ->join('s.organisateur', 'p')
            ->addSelect('p')
            ->join('p.campus_no_campus', 'c')
            ->addSelect('c')
            ->andWhere('s.id = :val')
            ->setParameter('val', $idSortie)
            ->getQuery()
            ->getOneOrNullResult()
            ;

    }
}
