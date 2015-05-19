<?php
namespace Applisun\AireJeuxBundle\Twig;

use Applisun\AireJeuxBundle\Entity\Aire;

class GoogleMapsExtension extends \Twig_Extension
{
     private $environment = null;

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('googlemaps', array($this, 'getPosition'), array('is_safe' => array('html'))),
        );
    }

    public function getPosition(Aire $aire)
    {
        $data = array('aire' => array('longitude' => $aire->getLongitude(), 'latitude' => $aire->getLatitude()));
        return $this->environment->render('ApplisunAireJeuxBundle:Default:googlemaps.html.twig', $data);
    }

    public function getName()
    {
        return 'googlemaps_extension';
    }
}