<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AireController extends Controller
{
    /**
     * @Route("/aire/new", name="aire_new")
     */
    public function newAction()
    {
        $aireManager = $this->get('applisun_aire_jeux.aire_manager');

        $model = new MediaModel($mediaManager->create(), $this->container->getParameter('kernel.root_dir'));
        $form = $this->createForm('media', $model);

        if ($this->getRequest()->isMethod('POST')) {
            $form->submit($this->getRequest()->request->get($form->getName()));

            if ($form->isValid()) {
                $mediaManager->save($model->getUpdatedMedia());

                $this->get('session')->getFlashBag()->add('success', 'Le média a bien été créé.');

                return $this->redirect($this->generateUrl('backend_new'));
            }
        }

        return $this->render('LexikTopOrFlopBundle:Backend:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}

