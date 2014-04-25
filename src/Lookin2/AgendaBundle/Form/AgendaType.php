<?php

namespace Lookin2\AgendaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AgendaType extends AbstractType
{
        /**
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
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lookin2\AgendaBundle\Entity\Agenda'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lookin2_agendabundle_agenda';
    }
}
