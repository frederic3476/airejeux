<?php
namespace Applisun\AireJeuxBundle\EventDispatcher\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Applisun\AireJeuxBundle\Service\MailManager;
use Applisun\AireJeuxBundle\Service\ImageManager;
use Applisun\AireJeuxBundle\Entity\User;

class AlertListener {
    
    private $mailManager;
    private $imageManager;

    public function __construct(MailManager $mm, ImageManager $im)
    {
        $this->mailManager = $mm;
        $this->imageManager = $im;
    }
    
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->mailManager->sendAlert($entity);
        
        if ($entity instanceof User) {
                $file_name = $entity->getFileName();
                    if ($file_name){
                        $this->imageManager->createImageFromOriginal($file_name, array('avatar' => array('w'=> 100, 'h' => 100, 'copyright' => false)), false);
                    }
            }
    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->mailManager->sendAlert($entity, false);        
    }
    
    /*public function postFlush(PostFlushEventArgs $args)
    {
        var_dump('toto');
        $em = $args->getEntityManager();
        foreach ($em->getUnitOfWork()->getScheduledEntityInsertions() as $entity) {
            var_dump($entity->get_class());
            if ($entity instanceof Applisun\AireJeuxBundle\Entity\User) {
                $file_name = $entity->getFileName();
                    if ($file_name){
                        $this->imageManager->createImageFromOriginal($file_name, array('avatar' => array('w'=> 100, 'h' => 100, 'copyright' => false)), false);
                    }
            }
        }                  
    }*/
    
}
