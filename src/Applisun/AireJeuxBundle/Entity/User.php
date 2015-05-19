<?php

namespace Applisun\AireJeuxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as FOSUser;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * User
 *
 * @ORM\Table(name="user",uniqueConstraints={
 *     @ORM\UniqueConstraint(name="nom", columns={"username"}),
 *     @ORM\UniqueConstraint(name="mail", columns={"email"})})
 * @ORM\Entity(repositoryClass="Applisun\AireJeuxBundle\Repository\UserRepository")
 * @ExclusionPolicy("all") 
 * 
 */
class User extends FOSUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;
    
    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    //protected $username;
    
    /**
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    //protected $email;
    

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Vote",
     *     mappedBy="user",
     *     fetch="EXTRA_LAZY"
     * )
     * @Serializer\Exclude()
     */
    protected $votes;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Aire",
     *     mappedBy="user",
     *     fetch="EXTRA_LAZY"
     * )
     * @Serializer\Exclude()
     */
    protected $aires;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Comment",
     *     mappedBy="user",
     *     fetch="EXTRA_LAZY"
     * )
     * @Serializer\Exclude()
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
        $this->aires = new ArrayCollection();
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
    
    /**
     * Add aire
     *
     * @param Aire $aire
     */
    public function addAire(Aire $aire)
    {
        $this->aires[] = $aire;
    }

    /**
     * Get aires
     *
     * @return ArrayCollection
     */
    public function getAires()
    {
        return $this->aires;
    }
    
    /**
     * Get the formatted name to display (NAME Firstname or username)
     * 
     * @return String
     * @VirtualProperty 
     */
    public function getUsedName(){
            return $this->getUsername();        
    }   
}

