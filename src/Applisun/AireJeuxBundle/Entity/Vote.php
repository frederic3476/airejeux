<?php

namespace Applisun\AireJeuxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Applisun\AireJeuxBundle\Entity\Aire;
use Applisun\AireJeuxBundle\Entity\User;

/**
 * @ORM\Table(
 *     name="vote",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="vote_unique_idx", columns={"user_id", "aire_id"})}
 * )
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity({"user", "aire"})
 */
class Vote
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer")
     * @Assert\NotBlank()
     * @Assert\LessThanOrEqual(
     *     value = 5
     * )
     */
    private $score;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * @var User $user
     *
     * @ORM\ManyToOne(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\User",
     *     inversedBy="votes"
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id"
     * )
     */
    private $user;
    
    /**
     * @var Aire $aire
     *
     * @ORM\ManyToOne(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Aire",
     *     inversedBy="votes"
     * )
     * @ORM\JoinColumn(
     *     name="aire_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )
     */
    private $aire;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
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
     * Set score
     *
     * @param integer $score
     *
     * @return vote
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return vote
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * Set user
     *
     * @param User $user
     *
     * @return Vote
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set aire
     *
     * @param Aire $aire
     *
     * @return Vote
     */
    public function setAire(Aire $aire)
    {
        $this->aire = $aire;

        return $this;
    }

    /**
     * Get aire
     *
     * @return Aire
     */
    public function getAire()
    {
        return $this->aire;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        if(!$this->getCreatedAt())
        {
          $this->created_at = new \DateTime();
        }
    }
}

