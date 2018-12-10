<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /** trouve un projet et ses eventuels langages
     * @param string $slug
     * @return Project|null
     */
    public function findOneWithLangages(string $slug):?Project
    {
        // Prepa de la requete
        // Select * from project where slug = : slug Join langages
        // where project.
        $query = $this->createQueryBuilder('proj')
            ->where('proj.slug = :slug')->setParameter('slug',$slug)
            ->leftJoin('proj.langages', 'l')
            ->addSelect('l')
            ->getQuery()
        ;
     // dump($query);
        try {
            return $query->getSingleResult();
        } catch (\Exception $e) {
            // dump ($e);
            return null;
        }
        // Exe requete
        // retour

    }
    // /**
    //  * @return Project[] Returns an array of Project objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Project
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
