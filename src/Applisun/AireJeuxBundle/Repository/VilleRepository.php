<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VilleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VilleRepository extends \Doctrine\ORM\EntityRepository
{
    public function getVilleByCompletion($queryString)
    {
        return $this->getEntityManager()
                ->createQuery('select v from ApplisunAireJeuxBundle:Ville v WHERE LOWER(v.nom) LIKE :query')
                ->setParameter('query', strtolower($queryString).'%')
                ->getResult();        
}
    
    public function getVilleActiveByDepartement(ContainerInterface $container, $idDepart,$page=1)
    {
        $maxperpage = $container->getParameter('maxperpage');
        
        $query = $this->getEntityManager()
		              ->createQuery('SELECT v FROM ApplisunAireJeuxBundle:Ville v INNER JOIN v.aires a WHERE v.departement = :id')
		              ->setParameter('id', $idDepart)
                              //->orderBy('v.nom', 'ASC') 
                              ->setFirstResult(($page-1) * $maxperpage)
                              ->setMaxResults($maxperpage);
 
        return new Paginator($query);
    }    
    
    public function getCountVilleActiveByDepartement($idDepart)
    {
        $query = $this->getEntityManager()
		              ->createQuery('SELECT v FROM ApplisunAireJeuxBundle:Ville v INNER JOIN v.aires a WHERE v.departement = :id')
		              ->setParameter('id', $idDepart);
        
        return count($query->getResult());
    }
    
}
