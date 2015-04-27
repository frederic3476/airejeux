<?php

namespace Applisun\AireJeuxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

use Applisun\AireJeuxBundle\Entity\Ville;

/**
 * Aire
 *
 * @ORM\Table(name="aire")
 * @ORM\Entity(repositoryClass="Applisun\AireJeuxBundle\Repository\AireRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Aire
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
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "5",
     *      max = "100",
     *      minMessage = "Le nom doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Le nom ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="surface", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Choice(callback = "getSurfaces")
     */
    private $surface;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", nullable=true)
     */
    private $latitude;
    
    /**
     * @var int
     *
     * @ORM\Column(name="age_min", type="integer")
     * @Assert\NotBlank()
     */
    private $ageMin;
    
    /**
     * @var int
     *
     * @ORM\Column(name="age_max", type="integer")
     * @Assert\NotBlank()
     * @Assert\LessThan(
     *     value = 18
     * )
     */
    private $ageMax;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255, nullable=true)
     */
    private $fileName;
    
    /**
     * @var float $average
     *
     * @ORM\Column(name="average", type="float", nullable=true)
     */
    private $average;

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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Vote",
     *     mappedBy="aire",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     *
     * @Assert\Valid()
     */
    private $votes;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Comment",
     *     mappedBy="aire",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     *
     * @Assert\Valid()
     */
    private $comments;
    
    /**
     * @var Ville $ville
     *
     * @ORM\ManyToOne(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Ville",
     *     inversedBy="aires"
     * )
     * @ORM\JoinColumn(
     *     name="ville_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )
     */
    private $ville;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Aire
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set surface
     *
     * @param string $surface
     *
     * @return Aire
     */
    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * Get surface
     *
     * @return string
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Aire
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Aire
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    
    /**
     * Set average
     *
     * @param float $average
     */
    public function setAverage($average)
    {
        $this->average = $average;

        return $this;
    }

    /**
     * Get average
     *
     * @return float
     */
    public function getAverage()
    {
        return $this->average;
    }
    
    /**
     * Add Vote
     *
     * @param Vote $vote
     */
    public function addVote(Vote $vote)
    {
        if ( ! $this->votes->contains($vote) ) {
	        $vote->setAire($this);
	        $this->votes->add($vote);

    	    $this->computeAverageScore();
        }
    }

    /**
     * Remove vote
     *
     * @param Vote $vote
     */
    public function removeVote(Vote $vote)
    {
        if ($this->votes->contains($vote)) {
            $vote->setAire(null);
            $this->votes->removeElement($vote);

            $this->computeAverageScore();
        }
    }

    /**
     * Get Votes
     *
     * @return ArrayCollection
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Calculate the average score of the media
     */
    public function computeAverageScore()
    {
        $count = count($this->votes);
        $total = 0;

        if (!$count) {
            return;
        }

        foreach ($this->votes as $vote) {
            $total += $vote->getScore();
        }

        $this->average = $total / $count;
    }

    /**
     * Average score formatted for display
     *
     * @return string
     */
    public function getDisplayedAverage()
    {
        return (null === $this->average) ? '-' : sprintf('%.1f', $this->average);
    }

    /**
     * Whether the user has already voted for this media or not
     *
     * @param User $user
     * @return boolean
     */
    public function hasUserAlreadyVoted(User $user)
    {
        foreach ($this->votes as $vote) {
            if ($vote->getUser() == $user) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Add Comment
     *
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        if ( ! $this->comments->contains($comment) ) {
	        $vote->setAire($this);
	        $this->comments->add($comment);
        }
    }
    
    /**
     * Remove comment
     *
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        if ($this->comments->contains($comment)) {
            $vote->setAire(null);
            $this->comments->removeElement($comment);
        }
    }

    /**
     * Get Comments
     *
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return Aire
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt     
     *
     * @return Aire
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
     * @return Aire
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
     * Set ville
     *
     * @param Ville $ville
     *
     * @return Aire
     */
    public function setVille(Ville $ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return Ville
     */
    public function getVille()
    {
        return $this->ville;
    }
    
    public static function getSurfaces()
    {
        return array('synthétique', 'sable', 'stabilisé', 'béton', 'pelouse', 'gravier');
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

