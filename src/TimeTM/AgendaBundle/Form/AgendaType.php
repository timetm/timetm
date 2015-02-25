<?php
/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\AgendaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form for Agenda CRUD
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
            ->add('name')
            ->add('description')
            ->add('user' ,   null , array( 'expanded' => false, 'multiple' => false , 'required' => true, 'label' => 'user' ))
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
            'data_class' => 'TimeTM\AgendaBundle\Entity\Agenda'
        ));
    }

    /**
     * get form name
     * 
     * @return string
     */
    public function getName()
    {
        return 'timetm_agendabundle_agenda';
    }
}
