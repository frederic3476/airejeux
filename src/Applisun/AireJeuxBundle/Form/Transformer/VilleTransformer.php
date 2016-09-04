<?php

namespace Applisun\AireJeuxBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Applisun\AireJeuxBundle\Entity\Ville;


/**
 * Class VilleTransformer
 * @package Applisun\AireJeuxBundle\Form\Transformer
 *
 * Data transformer utilisé dans Applisun\AireJeuxBundle\Form\Type\AireType.
 */
class VilleTransformer implements DataTransformerInterface
{
    
    /**
     * @var ObjectManager
     */
    private $om;
    
    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }
    
    /**
     * Transforms an object Ville to a string nom|code.
     *
     * @param  Ville|null $ville
     * @return string
     */
    public function transform($ville)
    {
        if (null === $ville) {
            return "";
        }

        return $ville->getNom()." (".$ville->getCode().")";
    }

    /**
     * Transforms a string nom|code to an object Ville.
     *
     * @param  string $str
     * @return Ville|null
     * @throws TransformationFailedException if object Ville is not found.
     */
    public function reverseTransform($str)
    {
        if (!$str) {
            return null;
        }
        
        //get code
        //$tab = explode('|', $str);
        //$nom = $tab[0];
        
        preg_match("/(.*) \((.*?)\)/", $str, $output_array);
        
        if (isset($output_array[2])){
        
        $code = $output_array[2];
        $nom = $output_array[1];

        $ville = $this->om
            ->getRepository('ApplisunAireJeuxBundle:Ville')
            ->findOneBy(array('nom' => $nom,'code' => $code));
        }
        else {
            $nom = $output_array[1];
            $ville = $this->om
            ->getRepository('ApplisunAireJeuxBundle:Ville')
            ->findOneBy(array('nom' => $nom));
        }
        
        
        if (null === $ville) {
            throw new TransformationFailedException(sprintf(
                'La ville ne peut pas être trouvée!',
                $str
            ));
        }
        
        return $ville;
    }
}
