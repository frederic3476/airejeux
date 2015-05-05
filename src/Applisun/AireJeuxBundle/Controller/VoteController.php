<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Applisun\AireJeuxBundle\Entity\Vote;

class VoteController extends Controller
{

/**
     * @Route("/aire/{id}/vote", name="vote_aire")
     * @Method({"POST"})
     *
     * @param integer $id
     */
    public function voteAireAction($id)
    {
        $aireManager = $this->get('applisun_aire_jeux.aire_manager');

        $aire = $aireManager->getAire($id);
        if ($aire === null) {
            throw $this->createNotFoundException('Aire non trouvée');
        }

        $vote = $aireManager->getNewVote($aire);

        $form = $this->createForm('vote_aire', $vote);
        $form->submit($this->getRequest());
        
         

        if ($form->isValid()) {
            $aireManager->saveVote($vote);

            $this->get('session')->getFlashBag()->add('success', 'Votre vote est enregistré.');

            return $this->redirect($this->generateUrl('aire_show', array('id' => $aire->getId())));
        }

        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue.');

        return $this->forward('ApplisunAireJeuxBundle:Aire:show', array(
            'id' => $aire->getId(),
        ));
    }
}

