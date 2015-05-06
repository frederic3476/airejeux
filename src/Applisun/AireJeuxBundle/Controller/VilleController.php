<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Applisun\AireJeuxBundle\Entity\Ville;

class VilleController extends Controller
{
    
    /**
     * @Route("/ville/{slug}/{id}/{page}", name="ville_show")
     */
    public function showAction(Request $request, $slug, $id, $page) {
        $em = $this->getDoctrine()->getEntityManager();
 
        $ville = $em->getRepository('ApplisunAireJeuxBundle:Ville')->find($id);
 
        if (!$ville) {
            throw $this->createNotFoundException('Unable to find Ville entity.');
        }
        
        $aires = $em->getRepository('ApplisunAireJeuxBundle:Aire')->getAireByVille($this->container, $ville->getId(), $page);
        
        $count_aire = $em->getRepository('ApplisunAireJeuxBundle:Aire')->getCountAireByVille($ville->getId());
        
        $pagination = array(
            'page' => $page,
            'route' => 'ville_show',
            'slug' => $slug,
            'id' => $id,
            'pages_count' => (!is_array($count_aire)?ceil($count_aire / $this->container->getParameter('maxperpage')):1),
            'nbr_operation' => (!is_array($count_aire)?$count_aire:0),
            'route_params' => array()
        );   
        
        return $this->render('ApplisunAireJeuxBundle:Ville:show.html.twig', array(
            'ville' => $ville,
            'aires' => $aires,
            'pagination' => $pagination
            
        ));
        
        
    }
    
    
    /**
     * @Route("/ville/completion/{query}", name="ville_completion", defaults={"_format"="json"}, options={"expose"=true})
     */
    public function completionAction(Request $request, $query)
    {
        $em = $this->getDoctrine()->getManager();
        
        if(!$query) {
            return new Response('erreur');
        }
        
        $villes = $em->getRepository('ApplisunAireJeuxBundle:Ville')->getVilleByCompletion(strtolower($query));
        
        if($request->isXmlHttpRequest()) {
            /*$response = new JsonResponse();
            $response->setData(array(
                "data", "tutu" 
            ));
            return $response;*/
            return $this->render('ApplisunAireJeuxBundle:Ville:list.json.twig', array('villes' => $villes));
        }
        else{     
            return $this->render('ApplisunAireJeuxBundle:Ville:list.json.twig', array('villes' => $villes));
            //return $this->redirect('index');
        }
        
    }
}

