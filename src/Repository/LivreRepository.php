<?php

namespace App\Repository;

use App\Entity\Livre;
use App\Entity\Genre;
use App\Controller\GenreController;
use App\Controller\SearchController;
use App\Controller\TextType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function searchName($data) {

        $string = $data['titre'];

    	$query = $this->getEntityManager()
                  ->createQuery("SELECT l FROM App\Entity\Livre l 
                                          WHERE UPPER(l.titre) LIKE UPPER('%$string%')");
    	return $query->getResult();
    }

    public function searchBook($data){
        $title = $data['titre'];
        $noteMin = $data['noteMin'];
        $noteMax = $data['noteMax'];

        $dateMin = $data['dateMin'];
        $dateMax = $data['dateMax'];

        if ($dateMin == NULL)
            $dateMin= date("Y-m-d", strtotime("0001-1-1"));
        else
            $dateMin= date_format($dateMin, 'Y-m-d');

        if ($dateMax == NULL)
            $dateMax= date("Y-m-d", strtotime("9999-1-1"));
        else
            $dateMax= date_format($dateMax, 'Y-m-d');

        if ($title == ""){
            $query = $this->getEntityManager()
                ->createQuery("SELECT l FROM App\Entity\Livre l 
                                WHERE (UPPER(l.titre) LIKE UPPER('%')) AND (l.note BETWEEN $noteMin AND $noteMax) AND (l.date_de_parution BETWEEN '$dateMin' AND '$dateMax')    ");
        }
        else{
            $query = $this->getEntityManager()
                ->createQuery("SELECT l FROM App\Entity\Livre l 
                                WHERE (UPPER(l.titre) LIKE UPPER('%$title%')) AND (l.note BETWEEN $noteMin AND $noteMax) AND (l.date_de_parution BETWEEN '$dateMin' AND '$dateMax')    ");
        }
        

        return $query->getResult();
    }

    public function best() {
    	$query = $this->getEntityManager()
                  ->createQuery("SELECT l FROM App\Entity\Livre l
				ORDER BY l.note DESC");
	    $query->setMaxResults(5);

    	return $query->getResult();
    }

    public function last() {
    	$query = $this->getEntityManager()
                  ->createQuery("SELECT l FROM App\Entity\Livre l
				ORDER BY l.id DESC");
	$query->setMaxResults(5);
    	return $query->getResult();
    }

    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
