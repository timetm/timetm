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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use TimeTM\CoreBundle\Form\ContactsTransformer;
use TimeTM\CoreBundle\Form\NullToEmptyTransformer;
use TimeTM\CoreBundle\Entity\AgendaRepository;
use TimeTM\CoreBundle\Entity\ContactRepository;

/**
 * Form for Event CRUD
 *
 * @author André Friedli <a@frian.org>
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
        $em              = $options['entity_manager'];
        $user            = $options['user'];
        $contactHelper   = $options['contactHelper'];
        $currentAgendaId = $options['currentAgenda'];

        // get current agenda
        $currentAgenda = null;
        if ($currentAgendaId) {
            $currentAgenda = $em->getRepository('TimeTMCoreBundle:Agenda')->find($currentAgendaId);
        }


        $builder
        	// TITLE
            ->add('title',  TextType::class, array(
                'label'         => 'event.title.label',
                'attr'          => array(
                    'placeholder'   => 'event.title.placeholder'
                )
            ))
            ->add('client', EntityType::class, array(
			    'class'         => 'TimeTMCoreBundle:Contact',
            	'choice_label'  => 'lastname',
           		'required'      => false,
            	'placeholder'   => 'event.client.placeholder',
                'label'         => 'contact.client',
		    	'query_builder' => function(ContactRepository $er) {
		        	return $er->createQueryBuilder('c')
		        		->where('c.client = 1')
		        	;
		    	},
            ))
            // START DATE
            ->add('startdate', DateTimeType::class, array(
            		'widget' => 'single_text',
            		'format' => 'dd/MM/yyyy HH:mm',
            		'label'  => 'event.date.label',
            		'attr'   => array('class'=>'date')
            ))
            // END DATE
            ->add('enddate', DateTimeType::class, array(
            		'widget' => 'single_text',
            		'format' => 'dd/MM/yyyy HH:mm',
            		'attr'   => array('class'=>'date')
            ))
            // FULLDAY
            ->add('fullday', CheckboxType::class, array('required' => false))
            // PLACE
            ->add('place', TextType::class, array(
                'label' => 'event.place.label',
                'attr'  => array(
                    'placeholder'   => 'event.place.placeholder'
                )
            ))
            // DESCRIPTION
            ->add(
            	$builder->create('description', TextareaType::class, array(
            		'required'   => false,
            		'empty_data' => '',
            		'attr'       => array('cols' => '20', 'rows' => '3'),
                    'label'      => 'event.description.label'
            	))
           		->addModelTransformer(new NullToEmptyTransformer())
            );

            // AGENDA
            if ($currentAgenda) {
                $builder->add('agenda', EntityType::class, array(
    			    'class'         => 'TimeTMCoreBundle:Agenda',
                    'label'         => 'agenda.name.sing',
                    'data'          => $currentAgenda,
    		    	'query_builder' => function(AgendaRepository $er) use ($user) {
    		        	return $er->createQueryBuilder('a')
    		        		->where('a.user = :user')
    		           		->orderBy('a.name')
    			        	->setParameter('user', $user);
    		    	},
    			));
            }
            else {
                $builder->add('agenda', EntityType::class, array(
                    'class'         => 'TimeTMCoreBundle:Agenda',
                    'label'         => 'agenda.name.sing',
                    'query_builder' => function(AgendaRepository $er) use ($user) {
                        return $er->createQueryBuilder('a')
                            ->where('a.user = :user')
                            ->orderBy('a.name')
                            ->setParameter('user', $user);
                    },
                ));
            }

			// PARTICIPANTS
        	$builder->add(
				$builder->create('participants', TextType::class, array(
					'required' => false,
                    'label'    => 'event.participants.label',
					'attr'     => array('placeholder' => 'event.participants.placeholder'),
				))
               	->addModelTransformer(new ContactsTransformer($em,$contactHelper))
       		)
       		// NON MAPPED : CONTACTS
			->add('contacts', EntityType::class, array(
            		'class'       => 'TimeTMCoreBundle:Contact',
            		'mapped'      => false,
					'required'    => false,
            		'placeholder' => 'event.participants.select',
                    'label'       => ' '
            ))
        ;
    }


    /**
     * configure OptionsResolver
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TimeTM\CoreBundle\Entity\Event'
        ));
        $resolver->setRequired('entity_manager');
        $resolver->setRequired('user');
        $resolver->setRequired('contactHelper');
        $resolver->setRequired('currentAgenda');
    }

    /**
     * get form name
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'timetm_eventbundle_event';
    }
}
