<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Applisun\AireJeuxBundle\Entity\Aire;

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

}
