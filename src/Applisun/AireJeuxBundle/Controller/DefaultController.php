<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Applisun\AireJeuxBundle\Highmap\Highmap;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        
        return  $this->render('ApplisunAireJeuxBundle:Default:index.html.twig');
        
        /*$ob = new HighchartMap();
        $ob->chart->renderTo('map');  
        $ob->title->text('');

        $ob->series($soldeHistory);
        
        return  $this->render('ApplisunAireJeuxBundle:Default:index.html.twig', array(
            'map' => $ob));
         * */
         
    }
}
