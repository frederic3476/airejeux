<?php

namespace Applisun\AireJeuxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Applisun\AireJeuxBundle\Entity\Departement;

/**
 * ville
 *
 * @ORM\Table(name="ville")
 * @ORM\Entity(repositoryClass="Applisun\AireJeuxBundle\Repository\villeRepository")
 */
class Ville
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
     * @ORM\Column(name="code", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="insee", type="string", length=255)
     */
    private $insee;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     * @Assert\NotBlank()
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     * @Assert\NotBlank()
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="codex", type="string", length=20)
     */
    private $codex;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Aire",
     *     mappedBy="ville",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     *
     * @Assert\Valid()
     */
    private $aires;
    
    /**
     * @var Departement $departement
     *
     * @ORM\ManyToOne(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Departement",
     *     inversedBy="villes"
     * )
     * @ORM\JoinColumn(
     *     name="departement_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )
     */
    private $departement;

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set code
     *
     * @param string $code
     *
     * @return ville
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return ville
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
     * Set slug
     *
     * @param string $slug
     *
     * @return ville
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set insee
     *
     * @param string $insee
     *
     * @return ville
     */
    public function setInsee($insee)
    {
        $this->insee = $insee;

        return $this;
    }

    /**
     * Get insee
     *
     * @return string
     */
    public function getInsee()
    {
        return $this->insee;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return ville
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
     * @return ville
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
     * Set codex
     *
     * @param string $codex
     *
     * @return ville
     */
    public function setCodex($codex)
    {
        $this->codex = $codex;

        return $this;
    }

    /**
     * Get codex
     *
     * @return string
     */
    public function getCodex()
    {
        return $this->codex;
    }
    
    /**
     * Add Aire
     *
     * @param Aire $aire
     */
    public function addAire(Aire $aire)
    {
        if ( ! $this->aires->contains($aire) ) {
	        $vote->setAire($this);
	        $this->aires->add($aire);
        }
    }
    
    /**
     * Remove aire
     *
     * @param Aire $aire
     */
    public function removeAire(Comment $aire)
    {
        if ($this->aires->contains($aire)) {
            $vote->setAire(null);
            $this->aires->removeElement($aire);
        }
    }

    /**
     * Get Aires
     *
     * @return ArrayCollection
     */
    public function getAires()
    {
        return $this->aires;
    }
    
    /**
     * Set departement
     *
     * @param Departement $departement
     *
     * @return Ville
     */
    public function setDepartement(Departement $departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return Departement
     */
    public function getDepartement()
    {
        return $this->departement;
    }
}

