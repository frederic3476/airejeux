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

