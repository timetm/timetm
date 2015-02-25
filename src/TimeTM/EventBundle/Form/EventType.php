<?php
/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form for Event CRUD
 */
class EventType extends AbstractType
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
            ->add('title',        'text')
            ->add('place',        'text')
            ->add('description',  'textarea')
            ->add('date',         'date')
            ->add('time',         'time')
            ->add('duration',     'text')
            ->add('participants', 'text')
            ->add('agenda',       'entity', array(
			    'class' => 'TimeTMAgendaBundle:Agenda',
			    'property' => 'name',
			))
        ;
    }
    
    /**
     * configure OptionsResolverInterface
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TimeTM\EventBundle\Entity\Event'
        ));
    }

    /**
     * get form name
     * 
     * @return string
     */
    public function getName()
    {
        return 'timetm_eventbundle_event';
    }
}
