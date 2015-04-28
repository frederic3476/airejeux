<?php

namespace Applisun\AireJeuxBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Applisun\AireJeuxBundle\Utils\TransformString;

/**
 * Class SlugifyCommand
 * @package Applisun\AireJeuxpBundle\Command
 */
class SlugifyVilleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('slug')
            ->setDescription('Slugify field');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {                
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
            
        $entities = $em->getRepository('ApplisunAireJeuxBundle:Ville')->findAll();
        foreach ($entities as $entitie)
        {
            $entitie->setSlug(TransformString::slugify($entitie->getNom()));            
        }
        $em->flush();
        
        $output->writeln('Ville slugified');
    }
}

