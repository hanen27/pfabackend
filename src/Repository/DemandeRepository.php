<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Demande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Demande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demande[]    findAll()
 * @method Demande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeRepository extends ServiceEntityRepository
{ private $manager;
    function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {     
        
        parent::__construct($registry, Demande::class);
        $this->manager = $manager;

    }
    function saveDemande($date_debut, $date_fin, $etat, $user)
    {    
        $newDemande = new Demande();

        $newDemande
            ->setDateDebut($date_debut)
            ->setDateFin($date_fin)
            ->setEtat($etat)
            ->setUser($user);
            
        $this->manager->persist($newDemande);
        $this->manager->flush();
    }
    public function updateDemande(Demande $demande): Demande
    {
        $this->manager->persist($demande);
        $this->manager->flush();
    
        return $demande;
    }
    public function removeUser(Demande $demande)
    {
        $this->manager->remove($demande);
        $this->manager->flush();
    }
    // /**
    //  * @return Demande[] Returns an array of Demande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Demande
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
