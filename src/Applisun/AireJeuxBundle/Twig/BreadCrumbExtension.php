<?php
namespace Applisun\AireJeuxBundle\Twig;

use Applisun\AireJeuxBundle\Entity\BreadCrumbInterface;

class BreadCrumbExtension extends \Twig_Extension
{
     private $environment = null;

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('breadcrumb', array($this, 'getBreadCrumb'), array('is_safe' => array('html'))),
        );
    }

    public function getBreadCrumb(BreadCrumbInterface $entity)
    {
        return $this->environment->render('ApplisunAireJeuxBundle:Default:breadcrumb.html.twig', $entity->getDataForBreadCrumb());
    }

    public function getName()
    {
        return 'breadcrumb_extension';
    }
}

