<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Applisun\AireJeuxBundle\Highmap\Highmap;
use Zend\Json\Expr;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {   
        $em = $this->getDoctrine()->getEntityManager();
        
        $ob = new HighMap();
        $ob->chart->renderTo('map');  
        $ob->title->text('');
        $ob->navigation(array("buttonOptions" => array("enabled" => false)));
        $ob->mapNavigation(array("enabled" => true, "buttonOptions" => array("verticalAlign" => "bottom")));
        
        $ob->plotOptions(array("series" => array(
                                    "point" => array(
                                        "events" => array(
                                            "click" => new Expr('function (){ '
                                                    . 'document.location.href=Routing.generate(\'departement_show\', { slug: this.slug, id: this.id, page: 1 }); '
                                                    . '}')
                                            )
                                        )
                                )
                            )
                        );
        
        $data= $em->getRepository('ApplisunAireJeuxBundle:Departement')->getAllAires();
        
        $data = array(array(
                "data" => $data,                  
                "mapData" => new Expr('Highcharts.maps[\'countries/fr/fr-all-all\']'),
                "joinBy" => "hc-key",
                "name" => "Aire de jeux",
                "cursor" => "pointer",
                "states" => array(
                    "hover" => array(
                        "color" => "#CCC"                       
                    )
                ),
                "dataLabels" => array(
                    "enabled" => true,
                    "format" => ""
                ),
                "tooltip" => array(
                    "valueSuffix" => ' aire(s)'    
                )
                ));
        $data2 = array(
                    "name" => "Separators",
                    "type" => "mapline",
                    "data" => new Expr('Highcharts.geojson(Highcharts.maps[\'countries/fr/fr-all-all\'], \'mapline\')'),
                    "color" => "silver",
                    "showInLegend" => false,
                    "enableMouseTracking" => false
                );
        
        array_push($data, $data2);
        
        $ob->series($data);   
        
        
        //return  $this->render('ApplisunAireJeuxBundle:Default:index.html.twig');
        return  $this->render('ApplisunAireJeuxBundle:Default:index.html.twig', array(
            'carte' => $ob));
    }
}
