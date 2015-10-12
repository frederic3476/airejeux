<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Applisun\AireJeuxBundle\Utils\TransformString;
use Applisun\AireJeuxBundle\Entity\Aire;
use Applisun\AireJeuxBundle\Entity\Vote;
use Applisun\AireJeuxBundle\Entity\Comment;
use Applisun\AireJeuxBundle\Position\AirePosition;
use Applisun\AireJeuxBundle\Utils\Maths;

class AireController extends Controller {

    /**
     * @Route("/aire/new", name="aire_new")
     */
    public function newAction(Request $request) {
        $aireManager = $this->get('applisun_aire_jeux.aire_manager');
        $entity = new Aire();
        $form = $this->createForm('applisun_aire_form', $entity, array('em' => $this->getDoctrine()->getManager()));
        $form->handleRequest($request);    
        if ($form->isValid()) {
            $aireManager->save($entity);
            
            //create thumbnail
            if ($entity->getFileName()){
                $this->get('applisun_aire_jeux.image_manager')->createImageFromOriginal($entity->getFileName(), array('normal' => 
                                                                                                                        array('w'=> 500, 'h' => 280, 'copyright' => true), 
                                                                                                                   'thumb'=> 
                                                                                                                        array('w'=> 100, 'h' => 56, 'copyright' => false )));
            }
            $this->get('session')->getFlashBag()->add('success', 'L\'aire de jeux a bien été créé.');

            return $this->redirect($this->generateUrl('index'));
        }


        return $this->render('ApplisunAireJeuxBundle:Aire:new.html.twig', array(
                    'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/aire/edit/{id}", name="aire_edit")
     *
     * Ici le traitement est le même que dans newAction sauf que l'on utilise un "form hanlder" afin de
     * déporter la logique de traitement du formulaire dans un service.
     */
    public function editAction($id)
    {
        $aire = $this->get('applisun_aire_jeux.aire_manager')->getAire($id);

        $handler = $this->get('applisun_aire_jeux.form.handler.aire');
        $form = $handler->createForm($aire);

        if ($handler->process($form, $this->getRequest())) {
            return $this->redirect($this->generateUrl('aire_show', array('id' => $aire->getId(), 'slug' => TransformString::slugify($aire->getNom()))));
        }

        return $this->render('ApplisunAireJeuxBundle:Aire:edit.html.twig', array(
            'form'  => $form->createView(),
            'aire' => $aire,
        ));
    }
    
    /**
     * @Route("/aire/delete/{id}", name="aire_delete")
     *
     * @param integer $id
     */
    public function aireDeleteAction($id) {
        $aireManager = $this->get('applisun_aire_jeux.aire_manager');
        $aire = $aireManager->getAire($id);
        
        if (!$aireManager->removeAire($aire)) {
            throw $this->createNotFoundException('Impossible de trouver l\'aire de jeux.');
        }
        $this->get('session')->getFlashBag()->add('success', 'Aire supprimée avec succès.');
        return $this->redirect($this->generateUrl('index'));
    }
    
    /**
     * @Route("/aire/{id}/{slug}", name="aire_show")
     */
    public function showAction($id, $slug) {
        $aireManager = $this->get('applisun_aire_jeux.aire_manager');
        $aire = $aireManager->getAire($id);
        
        if (!$aire instanceof Aire) {
            throw $this->createNotFoundException('Aucune aire trouvée !');
        }
        
        $vote = $aireManager->getNewVote($aire);        

        if ($vote instanceof Vote) {
            $form_vote = $this->createForm('vote_aire', $vote);
        }
        
        $comment = $aireManager->getNewComment($aire);
        
        if ($comment instanceof Comment) {
            $form_comment = $this->createForm('comment_aire', $comment);
        }
        
        $aP = new AirePosition();
        $aP->setIcon('http:\/\/'.$_SERVER['SERVER_NAME'].'/bundles/applisunairejeux/images/playground.png');
        $aP->setClassName('map');
        $aP->setElementId('map-canvas');
        $aP->setCenter(array('lat' => $aire->getLatitude(), 'lng' => $aire->getLongitude()));
        $aP->setZoom('10');
        $aP->addMarker(array('lat' => $aire->getLatitude(), 'lng' => $aire->getLongitude(), 'name' => $aire->getNom()));        
        
        return $this->render('ApplisunAireJeuxBundle:Aire:show.html.twig', array(
            'pos' => $aP,
            'aire' => $aire,
            'formVote'  => isset($form_vote) ? $form_vote->createView() : null,
            'formComment'  => isset($form_comment) ? $form_comment->createView() : null,
            'comments' => $aire->getComments(),
        ));
        
        
    }
    
    /**
     * @Route("/aire/morecommented", name="aire_morecommented")
     */
    public function moreCommentedAction() {
         $aires = $this->getDoctrine()->getRepository('ApplisunAireJeuxBundle:Aire')->getMoreCommentedAires($this->container->getParameter('limit'));
         
         $response = $this->render('ApplisunAireJeuxBundle:Aire:_listAire.html.twig', array('aires' => $aires));
         
         $response->setMaxAge(600);
         $response->setSharedMaxAge(600);
        
        return $response;
    }
    
    /**
     * @Route("/aire/newest", name="aire_newest")
     */
    public function newestAction() {
         $aires = $this->getDoctrine()->getRepository('ApplisunAireJeuxBundle:Aire')->getNewAires($this->container->getParameter('limit'));
         
         $response = $this->render('ApplisunAireJeuxBundle:Aire:_listAire.html.twig', array('aires' => $aires));
         $response->setMaxAge(600);
         $response->setSharedMaxAge(600);
        
        return $response;
    }
    
    /**
     * @Route("/aire/newest", name="aire_newest")
     */
    public function topAction() {
         $aires = $this->getDoctrine()->getRepository('ApplisunAireJeuxBundle:Aire')->getTopAires($this->container->getParameter('limit'));
         
         $response = $this->render('ApplisunAireJeuxBundle:Aire:_listAire.html.twig', array('aires' => $aires));
         $response->setMaxAge(600);
         $response->setSharedMaxAge(600);
        
        return $response;
    }
    
    /**
     * @Route("/aire/near/{latitude}/{longitude}/{perimeter}", name="aire_near", options={"expose"=true})
     */
    public function nearAction(Request $request, $latitude, $longitude, $perimeter) {
        
         $aires = $this->getDoctrine()->getRepository('ApplisunAireJeuxBundle:Aire')->getNearAires($latitude, $longitude, $perimeter);
         $result = array();
         //TODO calcul distance for sort
         foreach ($aires as $aire){
             $distance = Maths::distance($latitude, $longitude, $aire->getLatitude(), $aire->getLongitude());
             
             $result[] = array('id'=> $aire->getId(),  
                            "nom" => $aire->getNom(),
                            "filename" => $aire->getFileName(),
                            "average" => $aire->getAverage(),
                            "latitude" => $aire->getLatitude(),
                            "longitude" => $aire->getLongitude(),
                            "distance" => $distance,
                            "ville" => array('id'=>$aire->getVille()->getId(), 
                                             'nom'=> $aire->getVille()->getNom(), 
                                             'slug' => $aire->getVille()->getSlug()));
             
         } 
         
         usort($result, array("\Applisun\AireJeuxBundle\Controller\AireController", "cmp")); 
         
         if($request->isXmlHttpRequest()) {
             $response = $this->render('ApplisunAireJeuxBundle:Search:_listeAire.html.twig', array('aires' => $result, 'data' => array('latitude' => $latitude,
                                                                                                                                'longitude' => $longitude,
                                                                                                                                'perimeter' => $perimeter)));
         }
         else{
            $response = $this->render('ApplisunAireJeuxBundle:Aire:_listAire.html.twig', array('aires' => $result, 'data' => array('latitude' => $latitude,
                                                                                                                                'longitude' => $longitude,
                                                                                                                                'perimeter' => $perimeter)));
            $response->setMaxAge(600);
            $response->setSharedMaxAge(600);                                                                                                                  
         }         
        
        return $response;
    }
    
    static function cmp($a, $b){
        return ($a["distance"] > $b["distance"]) ? +1 : -1;
    }

}
