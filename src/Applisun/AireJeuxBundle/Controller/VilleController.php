<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Applisun\AireJeuxBundle\Position\AirePosition;

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
        
        $aP = new AirePosition();
        $aP->setIcon('http:\/\/'.$_SERVER['SERVER_NAME'].'/bundles/applisunairejeux/images/playground.png');
        $aP->setClassName('map');
        $aP->setElementId('map-canvas');
        $aP->setCenter(array('lat' => $ville->getLatitude(), 'lng' => $ville->getLongitude()));
        $aP->setZoom('11');
        foreach ($aires as $aire){
            $aP->addMarker(array('lat' => $aire->getLatitude(), 'lng' => $aire->getLongitude(), 'name' => $aire->getNom()));        
        }
        
        return $this->render('ApplisunAireJeuxBundle:Ville:show.html.twig', array(
            'pos' => $aP,
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
            return new \Symfony\Component\Security\Core\Exception\AccessDeniedException("accés refusé");
        }
        
    }
    
    /**
     * @Route("/ville/search/", name="ville_search")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $this->getRequest()->get('query');
 
        if(!$query) {
            return $this->redirect($this->generateUrl('index'));
        }
        
        preg_match("/(.*) \((.*?)\)/", $query, $output_array);
        if (isset($output_array[2]))
        {
            $code = $output_array[2];
            $nom = $output_array[1];
        
            $ville = $em->getRepository('ApplisunAireJeuxBundle:Ville')->findOneBy(array('nom' => $nom,'code' => $code));
            return $this->redirect($this->generateUrl('ville_show', array('slug' => $ville->getSlug(), 'id' => $ville->getId(), 'page' => 1)));
        }
        else{
            $villes = $em->getRepository('ApplisunAireJeuxBundle:Ville')->getVilleByCompletion(strtolower($query));
            return $this->render('ApplisunAireJeuxBundle:Ville:search.html.twig', array('villes' => $villes));
        }
    }
}

