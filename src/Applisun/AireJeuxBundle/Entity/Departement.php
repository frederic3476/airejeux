<?php

namespace Applisun\AireJeuxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * departement
 *
 * @ORM\Table(name="departement")
 * @ORM\Entity(repositoryClass="Applisun\AireJeuxBundle\Repository\DepartementRepository")
 */
class Departement
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
     * @ORM\Column(name="soundex", type="string", length=20)
     */
    private $soundex;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="hc_key", type="string", length=20)
     */
    private $hcKey;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Applisun\AireJeuxBundle\Entity\Ville",
     *     mappedBy="departement",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     *
     * @Assert\Valid()
     */
    private $villes;

    public function __construct()
    {
        $this->villes = new ArrayCollection();
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
     * @return departement
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
     * @return departement
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
     * @return departement
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
     * Set soundex
     *
     * @param string $soundex
     *
     * @return departement
     */
    public function setSoundex($soundex)
    {
        $this->soundex = $soundex;

        return $this;
    }

    /**
     * Get soundex
     *
     * @return string
     */
    public function getSoundex()
    {
        return $this->soundex;
    }
    
    /**
     * Set hcKey
     *
     * @param string $soundex
     *
     * @return departement
     */
    public function setHcKey($hcKey)
    {
        $this->hcKey = $hcKey;

        return $this;
    }

    /**
     * Get hcKey
     *
     * @return string
     */
    public function getHcKey()
    {
        return $this->hcKey;
    }
    
    /**
     * Add Ville
     *
     * @param Ville $ville
     */
    public function addVille(Ville $ville)
    {
        if ( ! $this->villes->contains($ville) ) {
	        $vote->setDepartement($this);
	        $this->villes->add($ville);
        }
    }
    
    /**
     * Remove ville
     *
     * @param Ville $ville
     */
    public function removeVille(Ville $ville)
    {
        if ($this->villes->contains($ville)) {
            $vote->setDepartement(null);
            $this->villes->removeElement($ville);
        }
    }

    /**
     * Get Villes
     *
     * @return ArrayCollection
     */
    public function getVilles()
    {
        return $this->villes;
    }
}

