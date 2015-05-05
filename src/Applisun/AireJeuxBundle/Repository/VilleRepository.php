<?php

namespace Applisun\AireJeuxBundle\Repository;

/**
 * AireRepository
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
    
}
