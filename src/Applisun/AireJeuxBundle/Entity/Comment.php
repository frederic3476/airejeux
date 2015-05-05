<?php

namespace Applisun\AireJeuxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Applisun\AireJeuxBundle\Entity\Aire;
use Applisun\AireJeuxBundle\Entity\User;

/**
 * comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="Applisun\AireJeuxBundle\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
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
     * @var string
     *
     * @ORM\Column(name="texte", type="text")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "2",
     *      max = "500",
     *      minMessage = "Votre commentaire doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre commentaire ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $texte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;
    
    /**
     * @var User $user
     *
     * @ORM\ManyToOne(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\User",
     *     inversedBy="comments"
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )
     */
    private $user;
    
    /**
     * @var Aire $aire
     *
     * @ORM\ManyToOne(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Aire",
     *     inversedBy="comments"
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
     * Set texte
     *
     * @param string $texte
     *
     * @return comment
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return comment
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return comment
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updated_at = new \DateTime();
    }
    
    
}

