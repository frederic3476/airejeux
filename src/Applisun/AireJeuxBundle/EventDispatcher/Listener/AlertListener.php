<?php
namespace Applisun\AireJeuxBundle\EventDispatcher\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Applisun\AireJeuxBundle\Service\MailManager;

class AlertListener {
    
    private $mailManager;
    
    public function __construct(MailManager $mm)
    {
        $this->mailManager = $mm;
    }
    
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        $this->mailManager->sendAlert($entity);   
    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        $this->mailManager->sendAlert($entity, false);        
    }
}
