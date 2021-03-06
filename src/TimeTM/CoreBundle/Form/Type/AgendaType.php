<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form for Agenda CRUD
 *
 * @author André Friedli <a@frian.org>
 */
class AgendaType extends AbstractType
{
    /**
     * create the form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null , array('label' => 'agenda.namefield.label'))
            ->add('description', null , array('label' => 'event.description.label'))
            ->add('user', null , array('expanded' => false, 'multiple' => false , 'required' => true, 'label' => 'user'))
            ->add('default', null , array('label' => 'agenda.default.label'))
        ;
    }

    /**
     * configure OptionsResolverInterface
     *
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TimeTM\CoreBundle\Entity\Agenda'
        ));
    }

    /**
     * get form name
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'timetm_agendabundle_agenda';
    }
}
