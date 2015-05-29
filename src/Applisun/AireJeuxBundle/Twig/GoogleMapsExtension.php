<?php
namespace Applisun\AireJeuxBundle\Twig;

use Applisun\AireJeuxBundle\Position\PositionInterface;

class GoogleMapsExtension extends \Twig_Extension
{     
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('googlemaps', array($this, 'getPosition'), array('is_safe' => array('html'))),
        );
    }

    public function getPosition(PositionInterface $position)
    {
        return $position->render();
    }

    public function getName()
    {
        return 'googlemaps_extension';

    }
}