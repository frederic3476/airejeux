<?php

namespace Applisun\AireJeuxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Validator\Constraints as Assert;
use Applisun\AireJeuxBundle\Entity\BreadCrumbInterface;
use Applisun\AireJeuxBundle\Entity\Ville;

/**
 * Aire
 *
 * @ORM\Table(name="aire")
 * @ORM\Entity(repositoryClass="Applisun\AireJeuxBundle\Repository\AireRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Aire implements BreadCrumbInterface
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
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     * @Assert\File(maxSize="500k")
     */
    public $image;
    
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
     * @var User $user
     *
     * @ORM\ManyToOne(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\User",
     *     inversedBy="aires"
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id"
     * )
     */
    private $user;

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
     * Set ageMin
     *
     * @param float $ageMin
     *
     * @return Aire
     */
    public function setAgeMin($ageMin)
    {
        $this->ageMin = $ageMin;

        return $this;
    }

    /**
     * Get ageMin
     *
     * @return integer
     */
    public function getAgeMin()
    {
        return $this->ageMin;
    }
    
    /**
     * Set ageMax
     *
     * @param float $ageMax
     *
     * @return Aire
     */
    public function setAgeMax($ageMax)
    {
        $this->ageMax = $ageMax;

        return $this;
    }

    /**
     * Get ageMax
     *
     * @return integer
     */
    public function getAgeMax()
    {
        return $this->ageMax;
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
     * Whether the user has already voted for this aire or not
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
     * Whether the user has already commented for this aire or not
     *
     * @param User $user
     * @return boolean
     */
    public function hasUserAlreadyCommented(User $user)
    {
        foreach ($this->comments as $comment) {
            if ($comment->getUser() == $user) {
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
	        $comment->setAire($this);
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
     * Get image
     *
     * @return UploadedFile
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param UploadedFile $file
     */
    public function setImage(UploadedFile $file = null)
    {
        $this->image = $file;
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
    
    protected function getUploadDir()
    {
        return 'uploads/aires';
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getWebPath()
    {
        return null === $this->fileName ? null : $this->getUploadDir().'/'.$this->fileName;
    }

    public function getAbsolutePath()
    {
        return null === $this->fileName ? null : $this->getUploadRootDir().'/'.$this->fileName;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preUpload()
    {
        if (null !== $this->image) {
            // do whatever you want to generate a unique name
            $this->fileName = uniqid().'.'.$this->image->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist
     * @ORM\postUpdate
     */
    public function upload()
    {
        if (null === $this->image) {
        return;
      }

      // if there is an error when moving the image, an exception will
      // be automatically thrown by move(). This will properly prevent
      // the entity from being persisted to the database on error
      $this->image->move($this->getUploadRootDir(), $this->fileName);

      unset($this->image);
        }

    /**
     * @ORM\PostRemove
     */
    public function removeUpload()
    {
        if ($image = $this->getAbsolutePath()) {
            unlink($image);
          }
    }
    
    public function getDataForBreadCrumb()
    {
        return array("data" => array(
                        array('url' => 'departement_show', 
                              'name' => $this->getVille()->getDepartement()->getNom(), 
                              'slug' => $this->getVille()->getDepartement()->getSlug(), 
                              'id' => $this->getVille()->getDepartement()->getId()),
                        array('url' => 'ville_show', 
                              'name' => $this->getVille()->getNom(), 
                              'slug' => $this->getVille()->getSlug(), 
                              'id' => $this->getVille()->getId()),
                        array('name' => $this->getNom())
                        )
                    );
    }
}

