<?php

namespace Applisun\AireJeuxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SearchType
 * @package Applisun\AireJeuxBundle\Form\Type
 */
class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', 'text', array('required'  => true, 'label' => 'Votre nom'))
                ->add('email', 'email', array('required'  => true, 'label' => 'Votre email'))
                ->add('message', 'textarea', array('required' => true, 'label' => 'Votre message'));
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'applisun_contact_form';
    }
}

