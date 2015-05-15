<?php

namespace Applisun\AireJeuxBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

use Applisun\AireJeuxBundle\Entity\User;
use Applisun\AireJeuxBundle\Entity\Aire;
use Applisun\AireJeuxBundle\Entity\Vote;
use Applisun\AireJeuxBundle\Entity\Comment;

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
    
    /**
     * Get a aire object from an id
     *
     * @param integer $id
     * @return Aire
     */
    public function getAire($id)
    {
        $aireRepository = $this->em->getRepository('Applisun\AireJeuxBundle\Entity\Aire');
        $aire = $aireRepository->find($id);

        return $aire instanceof Aire ? $aire : null;
    }
    
    /**
     * Returns an empty aire instance
     *
     * @return Aire
     */
    public function createAire()
    {        
        $aire = new Aire();

        return $aire;
    }
    
    /**
     * Get a new vote object for current user and given aire
     *
     * @param Aire $aire
     * @return Vote
     */
    public function getNewVote(Aire $aire)
    {
        $user = $this->context->getToken()->getUser();

        if (!$user instanceof User) {
            return null;
        }

        $vote = new Vote();
        $vote->setUser($user);
        $vote->setAire($aire);

        return $vote;
    }
    
     /**
     * Get a new comment object for current user and given aire
     *
     * @param Aire $aire
     * @return Comment
     */
    public function getNewComment(Aire $aire)
    {
        $user = $this->context->getToken()->getUser();

        if (!$user instanceof User) {
            return null;
        }

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setAire($aire);

        return $comment;
    }
    
    /**
     * Save a vote and update the average score
     *
     * @param Vote $vote
     */
    public function saveVote(Vote $vote)
    {
        $aire = $vote->getAire();
        $aire->addVote($vote);

        $this->em->flush();
    }
    
    /**
     * Save a vote and update the average score
     *
     * @param Comment $comment
     */
    public function saveComment(Comment $comment)
    {
        $aire = $comment->getAire();
        $aire->addComment($comment);

        $this->em->flush();
    }
    
}

