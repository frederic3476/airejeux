<?php

namespace Applisun\AireJeuxBundle\DataFixtures\ORM;
 
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Applisun\AireJeuxBundle\Entity\Ville;
 
class LoadVilleData extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $em)
  {
    $villes = array(
            array('departement_id'=> 1, 
                'code' => '01500', 
                'nom' => 'Ambérieu-en-Bugey', 
                'slug' => 'Ambérieu-en-Bugey', 
                'insee' => '01004', 
                'longitude' => 45.979851,
                'latitude' => 5.33689, 
                'codex' => 'A516'),
        );
      
    foreach($villes as $ville){            
        $v = new Ville(); 
        $v->setNom($ville['nom']);
        $v->setCode($ville['code']);
        $v->setSlug($ville['slug']);
        $v->setSlug($ville['slug']);
        $em->persist($v);
    }
    
    $em->flush();
  }
  
  public function getOrder()
  {
    return 1; // the order in which fixtures will be loaded
  }
}


