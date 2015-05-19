<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Applisun\AireJeuxBundle\Entity\Aire;
use Applisun\AireJeuxBundle\Entity\Vote;
use Applisun\AireJeuxBundle\Entity\Comment;

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
            return $this->redirect($this->generateUrl('aire_edit', array('id' => $aire->getId())));
        }

        return $this->render('ApplisunAireJeuxBundle:Aire:edit.html.twig', array(
            'form'  => $form->createView(),
            'aire' => $aire,
        ));
    }
    
    /**
     * @Route("/aire/show/{id}", name="aire_show")
     */
    public function showAction($id) {
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
        

        return $this->render('ApplisunAireJeuxBundle:Aire:show.html.twig', array(
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
         
         return $this->render('ApplisunAireJeuxBundle:Aire:_listAire.html.twig', array('aires' => $aires));
    }
    
    /**
     * @Route("/aire/newest", name="aire_newest")
     */
    public function newestAction() {
         $aires = $this->getDoctrine()->getRepository('ApplisunAireJeuxBundle:Aire')->getNewAires($this->container->getParameter('limit'));
         
         return $this->render('ApplisunAireJeuxBundle:Aire:_listAire.html.twig', array('aires' => $aires));
    }
    
    /**
     * @Route("/aire/newest", name="aire_newest")
     */
    public function topAction() {
         $aires = $this->getDoctrine()->getRepository('ApplisunAireJeuxBundle:Aire')->getTopAires($this->container->getParameter('limit'));
         
         return $this->render('ApplisunAireJeuxBundle:Aire:_listAire.html.twig', array('aires' => $aires));
    }

}
