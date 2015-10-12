<?php
namespace Applisun\AireJeuxBundle\Twig;

use Applisun\AireJeuxBundle\Position\PositionInterface;

class GoogleMapsExtension extends \Twig_Extension
{     
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('googlemaps', array($this, 'getPosition'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('googledistance', array($this, 'getDistance'), array('is_safe' => array('html'))),
        );
    }

    public function getPosition(PositionInterface $position)
    {
        return $position->render();
    }
    
    public function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $return = "var latLng1 = new google.maps.LatLng(".floatval($lat1).", ".floatval($lng1).");\n"
                . "var latLng2 = new google.maps.LatLng(".floatval($lat2).", ".floatval($lng2).");\n"
                . "var distance = google.maps.geometry.spherical.computeDistanceBetween(latLng1, latLng2);\n"
                . "document.getElementById('distanceKM').innerHTML = distance+' km'";
        
        return $return;
    }

    public function getName()
    {
        return 'googlemaps_extension';

    }
}