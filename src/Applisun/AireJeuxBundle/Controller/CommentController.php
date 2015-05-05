<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Applisun\AireJeuxBundle\Entity\Comment;

class CommentController extends Controller
{

/**
     * @Route("/aire/{id}/comment", name="comment_aire")
     * @Method({"POST"})
     *
     * @param integer $id
     */
    public function commentAireAction($id)
    {
        $aireManager = $this->get('applisun_aire_jeux.aire_manager');

        $aire = $aireManager->getAire($id);
        if ($aire === null) {
            throw $this->createNotFoundException('Aire non trouvée');
        }

        $comment = $aireManager->getNewComment($aire);

        $form = $this->createForm('comment_aire', $comment);
        $form->submit($this->getRequest());

        if ($form->isValid()) {
            $aireManager->saveComment($comment);

            $this->get('session')->getFlashBag()->add('success', 'Votre commentaire est enregistré.');

            return $this->redirect($this->generateUrl('aire_show', array('id' => $aire->getId())));
        }

        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue.');

        return $this->forward('ApplisunAireJeuxBundle:Aire:showAire', array(
            'id' => $aire->getId(),
        ));
    }
}

