<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Applisun\AireJeuxBundle\Form\Type\SearchType;

class SearchController extends Controller {

    /**
     * @Route("/search/{page}", name="search", options={"expose"=true})
     */
    public function searchAction($page = 1) {
        $form = $this->createForm(new SearchType());

        $request = $this->getRequest();
        
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $data = array('departement'=>$request->query->get('departement'),
                          'ville'=>$request->query->get('ville'),
                            'ageMin'=>$request->query->get('ageMin'),
                            'ageMax'=>$request->query->get('ageMax'),
                            'nbrJeuxMin'=>$request->query->get('nbrJeuxMin'),
                            'noteMin'=>$request->query->get('noteMin'),
                            '_token'=>$request->query->get('_token'),
                            'is_picnic'=>($request->query->get('is_picnic')?true:false),
                            'is_sport'=>($request->query->get('is_sport')?true:false),
                            'is_shadow'=>($request->query->get('is_shadow')?true:false));
            
            $aires = $em->getRepository('ApplisunAireJeuxBundle:Aire')->findAireByParametres($this->container, $data, $page);                
                
            return $this->render('ApplisunAireJeuxBundle:Search:_listeAire.html.twig', array('aires' => $aires));
        }
        

        elseif ($request->getMethod() == 'GET') {

            $form->bind($request);

            if ($form->isValid()) {
                
                $em = $this->getDoctrine()->getManager();

                $data = $request->query->get('applisun_search_form');                
                
                $aires = $em->getRepository('ApplisunAireJeuxBundle:Aire')->findAireByParametres($this->container, $data, $page);                
                
                return $this->render('ApplisunAireJeuxBundle:Search:listeAire.html.twig', array('aires' => $aires, 'data' => $data));
            }
        }

        return $this->render('ApplisunAireJeuxBundle:Search:index.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * @Route("/closest", name="closest", options={"expose"=true})
     */
    public function closestAction() {
        $request = $this->getRequest();
        
        if($request->isXmlHttpRequest()) {
            if ($request->query->get('latitude') && $request->query->get('longitude') && $request->query->get('perimeter')){
                $em = $this->getDoctrine()->getManager();
                $aires = $em->getRepository('ApplisunAireJeuxBundle:Aire')->getNearAires($request->query->get('latitude'), $request->query->get('longitude'), $request->query->get('perimeter'));                                               
                return $this->render('ApplisunAireJeuxBundle:Search:_listeAire.html.twig', array('aires' => $aires));
            }
        }
        
        throw $this->createNotFoundException('Erreur position !');
    }

}
