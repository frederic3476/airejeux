<?php
namespace Applisun\AireJeuxBundle\Twig;

class DisplayAverageExtension extends \Twig_Extension
{   
    private $environment = null;

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('displayAverage', array($this, 'getAverage'), array('is_safe' => array('html'))),
        );
    }

    public function getAverage($average)
    {
        return $this->environment->render('ApplisunAireJeuxBundle:Default:average.html.twig', array('average' => (double) $average));
    }

    public function getName()
    {
        return 'average_extension';
    }
}

