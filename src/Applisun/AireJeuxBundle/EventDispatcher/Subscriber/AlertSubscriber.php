<?php

namespace Applisun\AireJeuxBundle\EventDispatcher\Subscriber;

use Applisun\AireJeuxBundle\EventDispatcher\Event\AlertEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AlertSubscriber
 * @package Applisun\AireJeuxBundle\EventDispatcher\Subscriber
 */
class AlertSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            AlertEvents::POST_UPDATE => array('onPostUpdate', 10),            
            AlertEvents::POST_ADD => array('onPostAdd', 0),
        );
    }

    public function onPostUpdate(Event $event)
    {
        $entity = $args->getEntity();
        
        if ($entity instanceof \Applisun\AireJeuxBundle\Entity\Aire) {
            var_dump('aire modifiée:'.$entity->getNom());exit;
        }


        // do something with $operation
    }

    public function onPostAdd(Event $event)
    {
        $entity = $args->getEntity();
        
        if ($entity instanceof \Applisun\AireJeuxBundle\Entity\Aire) {
            var_dump('aire ajoutée:'.$entity->getNom());exit;
        }
    }
}
