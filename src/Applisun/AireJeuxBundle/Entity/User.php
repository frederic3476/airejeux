<?php

namespace Applisun\AireJeuxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as FOSUser;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * User
 *
 * @ORM\Table(name="user",uniqueConstraints={
 *     @ORM\UniqueConstraint(name="nom", columns={"username"}),
 *     @ORM\UniqueConstraint(name="mail", columns={"email"})})
 * @ORM\Entity(repositoryClass="Applisun\AireJeuxBundle\Repository\UserRepository")
 * @ExclusionPolicy("all") 
 * @ORM\HasLifecycleCallbacks()
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
     * Encrypted password. Must be persisted.
     *
     * @var string
     * 
     * @Serializer\Exclude()
     */
    protected $password;
    
    
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
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255, nullable=true)
     */
    private $fileName;
    
    
    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     * @Assert\Image(maxSize="20k", minWidth=40, minHeight=40, maxWidth=100, maxHeight=100, 
     * minWidthMessage="l'avatar doit faire au moins 40px de largeur")
     * minHeightMessage="l'avatar doit faire au moins 40px de hauteur")
     * maxWidthMessage="l'avatar doit faire moins de 100px de largeur")
     * maxHeightMessage="l'avatar doit faire moins de 100px de hauteur")
     */
    public $image;

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
     * Set fileName
     *
     * @param string $fileName
     *
     * @return User
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
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updated_at = new \DateTime();
    }
    
    protected function getUploadDir()
    {
        return 'uploads/avatars';
    }

    public function getUploadRootDir()
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
       unlink($this->getAbsolutePath());
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

