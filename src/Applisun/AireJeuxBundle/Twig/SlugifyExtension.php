<?php
namespace Applisun\AireJeuxBundle\Twig;

use Applisun\AireJeuxBundle\Utils\TransformString;


class SlugifyExtension extends \Twig_Extension
{    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('slugify', array($this, 'slugify'), array('is_safe' => array('html'))),
        );
    }

    public function slugify($texte)
    {
        return TransformString::slugify($texte);
    }

    public function getName()
    {
        return 'slugify_extension';
    }
}
