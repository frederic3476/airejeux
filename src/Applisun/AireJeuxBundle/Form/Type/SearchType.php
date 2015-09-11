<?php

namespace Applisun\AireJeuxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SearchType
 * @package Applisun\AireJeuxBundle\Form\Type
 */
class SearchType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('departement', 'entity', array('class' => 'ApplisunAireJeuxBundle:Departement', 'property' => 'nom', 'empty_value' => 'Toute la France', 'required' => false, 'mapped' => false))
                ->add('ville', 'text', array('required'  => false))
                ->add('ageMin', 'choice', array(
                                        'choices' => array_combine(range(1, 5), range(1, 5))
                                        ))
                ->add('ageMax', 'choice', array(
                                        'choices' => array_combine(range(5, 18), range(5, 18))
                                        ))
                ->add('surface','choice',array(
                        'choices'   => array('NC' => 'NC',
                                                'synthétique' => 'synthétique',
                                                'sable' => 'sable',
                                                'stabilisé' => 'stabilisé',
                                                'béton' => 'béton',
                                                'pelouse' => 'pelouse',
                                                'gravier' => 'gravier'),
                        'required'  => false,
                    ))
                ->add('nbrJeuxMin', 'choice', array(
                                        'choices' => array_combine(range(1, 15), range(1, 15))
                                        ))
                ->add('noteMin', 'choice', array(
                                        'choices' => array_combine(range(0, 5), range(0, 5))
                                        ))
                ->add('is_picnic',  'checkbox', array( 'label' => 'Aire de pique-nique', 'required' => false))
                ->add('is_sport',  'checkbox', array( 'label' => 'Equipements sportifs', 'required' => false))
                ->add('is_shadow',  'checkbox', array( 'label' => 'Ombre', 'required' => false))  ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'applisun_search_form';
    }
}

