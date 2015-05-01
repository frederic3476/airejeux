<?php

namespace Applisun\AireJeuxBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

use Applisun\AireJeuxBundle\Entity\User;
use Applisun\AireJeuxBundle\Entity\Aire;

/**
 * Description of AireManager
 *
 */
class AireManager
{
    /**
     * @var SecurityContext
     */
    private $context;
    
    /**
     * @var EntityManager
     */
    private $em;
    
    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     * @param SecurityContext $securityContext
     */
    public function __construct(EntityManager $entityManager, SecurityContext $securityContext)
    {
        $this->em = $entityManager;
        $this->context = $securityContext;
    }
    
    /**
     * Persist and save a aire.
     *
     * @param Aire $aire
     */
    public function save(Aire $aire)
    {
        $user = $this->context->getToken()->getUser();

        if (!$user instanceof User) {
            return null;
        }
        
        $aire->setUser($user);
        
        $this->em->persist($aire);
        $this->em->flush();
    }
    
}

