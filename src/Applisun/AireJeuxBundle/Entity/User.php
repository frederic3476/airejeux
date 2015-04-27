<?php

namespace Applisun\AireJeuxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as FOSUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Applisun\AireJeuxBundle\Repository\UserRepository")
 */
class User extends FOSUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Vote",
     *     mappedBy="user",
     *     fetch="EXTRA_LAZY"
     * )
     */
    protected $votes;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Comment",
     *     mappedBy="user",
     *     fetch="EXTRA_LAZY"
     * )
     */
    protected $comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->votes = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Add vote
     *
     * @param Vote $vote
     */
    public function addVote(Vote $vote)
    {
        $this->votes[] = $vote;
    }

    /**
     * Get votes
     *
     * @return ArrayCollection
     */
    public function getVotes()
    {
        return $this->votes;
    }
    
    /**
     * Add comment
     *
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
    }

    /**
     * Get comments
     *
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }
}

