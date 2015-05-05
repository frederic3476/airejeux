<?php

namespace Applisun\AireJeuxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VoteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('score', 'choice', array(
            'choices' => array_combine(range(1, 5), range(1, 5)),
        ));

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
            'data_class'     => 'Applisun\AireJeuxBundle\Entity\Vote',
            'show_relations' => false,

            'show_user'      => function (Options $options) {
                return $options['show_relations'];
            },
            'show_aire'     => function (Options $options) {
                return $options['show_relations'];
            },
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'vote_aire';
    }
}
