<?php

namespace Applisun\AireJeuxBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

use Applisun\AireJeuxBundle\Entity\User;
use Applisun\AireJeuxBundle\Entity\Comment;

/**
 * Description of AireManager
 *
 */
class CommentManager
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
     * Get a comment object from an id
     *
     * @param integer $id
     * @return Comment
     */
    public function getComment($id)
    {
        $commentRepository = $this->em->getRepository('Applisun\AireJeuxBundle\Entity\Comment');
        $comment = $commentRepository->find($id);

        return $comment instanceof Comment ? $comment : null;
    }
    
    /**
     * Remove a comment object from an id
     *
     * @param Comment $comment
     * @return boolean
     */
    public function removeComment(Comment $comment)
    {
        if ($comment)
        {
            $this->em->remove($comment);
            $this->em->flush();                    
            return true;
        }
        return false;
    }
    
}

