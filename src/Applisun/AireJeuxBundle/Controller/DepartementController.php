<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Applisun\AireJeuxBundle\Entity\Ville;

class DepartementController extends Controller
{
    /**
     * @Route("/departement/{slug}/{id}/{page}", name="departement_show", options={"expose"=true})
     */
    public function showAction(Request $request, $slug, $id, $page) {
        
        $em = $this->getDoctrine()->getEntityManager();
 
        $departement = $em->getRepository('ApplisunAireJeuxBundle:Departement')->findOneBySlug($slug);
 
        if (!$departement) {
            throw $this->createNotFoundException('Unable to find Departement entity.');
        }
        
        $villes = $em->getRepository('ApplisunAireJeuxBundle:Ville')->getVilleActiveByDepartement($this->container, $departement->getId(), $page);
        
        $count_ville = $em->getRepository('ApplisunAireJeuxBundle:Ville')->getCountVilleActiveByDepartement($departement->getId());
        
        $pagination = array(
            'page' => $page,
            'route' => 'departement_show',
            'slug' => $slug,
            'id' => $id,
            'pages_count' => (!is_array($count_ville)?ceil($count_ville / $this->container->getParameter('maxperpage')):1),
            'nbr_operation' => (!is_array($count_ville)?$count_ville:0),
            'route_params' => array()
        );   
        
        return $this->render('ApplisunAireJeuxBundle:Departement:show.html.twig', array(
            'departement' => $departement,
            'villes' => $villes,
            'pagination' => $pagination
            
        ));
    }
}

