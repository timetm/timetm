<?php

namespace TimeTM\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',  TextType::class,     array('label' => 'task.title'))
            ->add('duedate', DateTimeType::class, array(
            		'widget' => 'single_text',
            		'format' => 'dd/MM/yyyy',
            		'label'  => 'event.date.label',
            		'attr'   => array('class'=>'date')
            ))
            ->add('userassigned', EntityType::class, array(
			    'class'       => 'TimeTMUserBundle:User',
                'placeholder' => 'assign to user',
                'required'    => false
            ))
            ->add('repetition')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TimeTM\CoreBundle\Entity\Task'
        ));
    }
}
