<?php

namespace Applisun\AireJeuxBundle\Form\Handler;

use Applisun\AireJeuxBundle\Entity\Aire;
use Applisun\AireJeuxBundle\Service\AireManager;
use Applisun\AireJeuxBundle\Service\ImageManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class AireFormHandler
 * @package Applisun\AireJeuxBundle\Form\Handler
 */
class AireFormHandler {

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $om;

    /**
     * @var \Applisun\AireJeuxBundle\Service\AireManager
     */
    private $manager;
    
    /**
     * @var \Applisun\AireJeuxBundle\Service\ImageManager
     */
    private $imanager;

    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactory;

    /**
     * @param ObjectManager $om
     * @param MediaManager $manager
     * @param FormFactory  $factory
     * @param string       $rootDir
     */
    public function __construct(ObjectManager $om, AireManager $manager, ImageManager $imanager, FormFactory $factory) {
        $this->om = $om;
        $this->manager = $manager;
        $this->imanager = $imanager;
        $this->formFactory = $factory;
    }

    /**
     * @param Aire $aire
     * @return \Symfony\Component\Form\Form
     */
    public function createForm(Aire $aire) {
        return $this->formFactory->create('applisun_aire_form', $aire, array('em' => $this->om));
    }

    /**
     * @param Form $form
     * @param Request $request
     * @return bool
     */
    public function process(Form $form, Request $request) {
        $valid = false;
        $form->handleRequest($request);

        if ($form->isValid()) {
            $aire = $form->getData();
            $this->manager->save($aire);
            $aire->upload();
            
            //create thumbnail
            $this->imanager->createImageFromOriginal($aire->getFileName(), array('normal' => 
                                                                                        array('w'=> 500, 'h' => 280), 
                                                                                    'thumb'=> 
                                                                                        array('w'=> 100, 'h' => 56 )));

            $request->getSession()->getFlashBag()->add('success', 'L\'aire de jeux a bien été modifiée.');

            $valid = true;
        }
        
        return $valid;
    }

}
