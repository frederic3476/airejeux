<?php

namespace Applisun\AireJeuxBundle\Service;

use Symfony\Component\Templating\EngineInterface;
use Applisun\AireJeuxBundle\Entity\User;
use Applisun\AireJeuxBundle\Entity\Aire;
use Applisun\AireJeuxBundle\Entity\Comment;

class MailManager {
    
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    
    
    protected $container;

    public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function setMailer(\Swift_Mailer $mailer)
    {
      $this->mailer = $mailer;
    }
    
    public function sendAlert($entity, $new = true)
    {   
        $data = array();
        $data["action"] = ($new?"ajouté":"modifié");
        $send = false;
        
        if ($entity instanceof Aire) {
            $data["type"] = "aire";
            $data["user"] = $entity->getUser()->getUsername();
            $data["info"] = "id :".$entity->getId();
            $send = true;
            
        }
        elseif ($entity instanceof Comment) {
            $data["type"] = "commentaire";  
            $data["user"] = $entity->getUser()->getUsername();
            $data["info"] = "aireID:". $entity->getAire()->getId()."\n Commentaire :".$entity->getTexte();
            $send = true;
        }
        elseif ($entity instanceof User) {
            $data["type"] = "utilisateur"; 
            $data["user"] = $entity->getUsername();
            $data["info"] = "email: ".$entity->getEmail();
            $send = $new;
        }
        
        if ($send){
        $message = \Swift_Message::newInstance()
                    ->setSubject('AireJeux.com')
                    ->setFrom('webmaster@airejeux.com')
                    ->setTo('frederic.teissier@live.fr')
                    ->setBody($this->container->get('templating')->render('ApplisunAireJeuxBundle:Mail:alert.txt.twig', array('data' => $data)));
        
        $this->mailer->send($message);
        }
    }
    
}

