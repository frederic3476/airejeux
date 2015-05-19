<?php

namespace Applisun\AireJeuxBundle\Repository;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * AireRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AireRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function getAireByVille(ContainerInterface $container, $villeId, $page)
    {
        $maxperpage = $container->getParameter('maxperpage');
        
        $query = $this->getEntityManager()
		              ->createQuery('SELECT a FROM ApplisunAireJeuxBundle:Aire a WHERE a.ville = :id')
		              ->setParameter('id', $villeId)
                              ->setFirstResult(($page-1) * $maxperpage)
                              ->setMaxResults($maxperpage);
 
        return new Paginator($query);
    }
    
    public function getCountAireByVille($villeId)
    {
        $query = $this->getEntityManager()
		              ->createQuery('SELECT a FROM ApplisunAireJeuxBundle:Aire a WHERE a.ville = :id')
		              ->setParameter('id', $villeId);
        
        return count($query->getResult());
    }
    
    public function getAllAireByVille($villeId)
    {
        return $this->getEntityManager()
		              ->createQuery('SELECT a FROM ApplisunAireJeuxBundle:Aire a WHERE a.ville = :id')
		              ->setParameter('id', $villeId)->getResult();
    }
    
    /**
     * Get 5 highest rated aire
     *
     * @return array
     */
    public function getTopAires($limit = 5)
    {
    	$qb = $this->createQueryBuilder('a');

        $query = $qb
            ->select('a, v')
            ->leftJoin('a.votes', 'v')
            ->where('a.average IS NOT NULL')
            ->orderBy('a.average', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults($limit)
            ->getQuery()
        ;

        return new Paginator($query, true);
    }
    
    /**
     * Get 5 newest aire
     *
     * @return array
     */
    public function getNewAires($limit = 5)
    {
    	$qb = $this->createQueryBuilder('a');

        $query = $qb
            ->select('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults($limit)
            ->getQuery()
        ;

        return new Paginator($query, true);
    }
    
    /**
     * Get 5 more commented aire
     *
     * @return array
     */
    public function getMoreCommentedAires($limit = 5)
    {
    	$query =  $this->getEntityManager()
            ->createQuery('SELECT a FROM ApplisunAireJeuxBundle:Aire a INNER JOIN a.comments c GROUP BY a.id ORDER BY c.id DESC')
            ->setFirstResult(0)
            ->setMaxResults($limit);
        
        return new Paginator($query, true);
    }
    
}
