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
            ->add('title',  TextType::class, array(
                'label' => 'task.title.label',
                'attr'  => array(
                    'placeholder'   => 'task.title.placeholder'
                )
            ))
            ->add('duedate', DateTimeType::class, array(
            		'widget' => 'single_text',
            		'format' => 'dd/MM/yyyy',
            		'label'  => 'event.date.label',
            		'attr'   => array('class'=>'date')
            ))
            ->add('userassigned', EntityType::class, array(
			    'class'       => 'TimeTMUserBundle:User',
                'label'       => 'task.user',
                'placeholder' => 'task.assigntouser.all',
                'required'    => false
            ))
            ->add('repetition', null, array('label' => 'task.repetition'))
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
