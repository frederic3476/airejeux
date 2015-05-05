<?php

namespace Applisun\AireJeuxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('texte', 'textarea', array('required' => true));

        if (true === $options['show_user']) {
            $builder->add('user', 'entity', array(
                'class' => 'Applisun\AireJeuxBundle\Entity\User',
            ));
        }

        if (true === $options['show_aire']) {
            $builder->add('aire', 'entity', array(
                'class' => 'Applisun\AireJeuxBundle\Entity\Aire',
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'     => 'Applisun\AireJeuxBundle\Entity\Comment',
            'show_relations' => false,

            'show_user'      => function (Options $options) {
                // $options Symfony\Component\OptionsResolver\Options
                return $options['show_relations'];
            },
            'show_aire'     => function (Options $options) {
                // $options Symfony\Component\OptionsResolver\Options
                return $options['show_relations'];
            },
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'comment_aire';
    }
}


