<?php

namespace Applisun\AireJeuxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Applisun\AireJeuxBundle\Form\Transformer\VilleTransformer;

/**
 * Class AireType
 * @package Applisun\AireJeuxBundle\Form\Type
 */
class AireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //need entityManager to transform string in object Ville
        $entityManager = $options['em'];
        
        $builder
            ->add('nom', 'text');
            $builder->add(
                $builder->create('ville', 'text')
                ->addModelTransformer(new VilleTransformer($entityManager)));
            $builder->add('surface','choice',array(
                        'choices'   => array('synthétique' => 'synthétique',
                                                'sable' => 'sable',
                                                'stabilisé' => 'stabilisé',
                                                'béton' => 'béton',
                                                'pelouse' => 'pelouse',
                                                'gravier' => 'gravier'),
                        'required'  => false,
                    ))
            ->add('longitude', 'text')
            ->add('latitude', 'text')
            ->add('ageMin', 'choice', array(
            'choices' => array_combine(range(1, 5), range(1, 5)),
            ))
            ->add('ageMax', 'choice', array(
            'choices' => array_combine(range(5, 18), range(5, 18)),
            ))
            ->add('image', 'file', array(
                'required' => false
            ));                        
    }
    
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            // Ici le formulaire utilise une data_class custom
            'data_class'         => 'Applisun\AireJeuxBundle\Entity\Aire',
        ));
        $resolver->setRequired(array(
            'em',
        ));
        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));        
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'applisun_aire_form';
    }
}

