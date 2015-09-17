<?php

namespace Applisun\AireJeuxBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
 
class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder->add('image', 'file', array(
                    'required' => false,
                    'image_path' => 'webPath'
                ));
    }

    public function getName()
    {
        return 'applisun_user_profile';
    }
}

