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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

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
	 * Entity Manager
	 *
	 * @var EntityManager $em
	 */
	private $em;

	/**
	 * Constructor
	 *
	 * @param EntityManager $em
	 * @param int $user
	 */
	public function __construct(EntityManager $em, $user) {
		$this->em = $em;
		$this->user = $user;
	}

    /**
     * create the form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$user = $this->user;

        $builder
        	// TITLE
            ->add('title',        'text')
            ->add('client', 'entity', array(
			    'class' => 'TimeTMCoreBundle:Contact',
            	'property' => 'lastname',
           		'required' => false,
            	'placeholder' => 'event.client.placeholder',
		    	'query_builder' => function(ContactRepository $er) {
		        	return $er->createQueryBuilder('c')
		        		->where('c.client = 1')
		        	;
		    	},
// 		    	'attr' => array('placeholder' => 'event.client.placeholder')
            ))
            // START DATE
            ->add('startdate',    'datetime', array(
            		'widget' => 'single_text',
            		'format' => 'dd/MM/yyyy HH:mm',
            		'label' => 'Date',
            		'attr' => array('class'=>'date')
            ))
            // END DATE
            ->add('enddate',      'datetime', array(
            		'widget' => 'single_text',
            		'format' => 'dd/MM/yyyy HH:mm',
            		'attr' => array('class'=>'date')
            ))
            // FULLDAY
            ->add('fullday',      'checkbox', array('required' => false))
            // PLACE
            ->add('place',        'text')
            // DESCRIPTION
            ->add(
            	$builder->create('description',  'textarea', array(
            		'required' => false,
            		'empty_data' => '',
            		'attr' => array('cols' => '40', 'rows' => '5')
            	))
           		->addModelTransformer(new NullToEmptyTransformer())
            )
            // AGENDA
            ->add('agenda',       'entity', array(
			    'class' => 'TimeTMCoreBundle:Agenda',
		    	'query_builder' => function(AgendaRepository $er) use ($user) {
		        	return $er->createQueryBuilder('a')
		        		->where('a.id = :user')
		           		->orderBy('a.name')
			        	->setParameter('user', $user);
		    	},
			))
			// PARTICIPANTS
        	->add(
				$builder->create('participants', 'text', array(
					'required' => false,
					'attr' => array('placeholder' => 'event.participants.placeholder'),
				))
               	->addModelTransformer(new ContactsTransformer($this->em))
       		)
       		// NON MAPPED : CONTACTS
			->add('contacts',     'entity', array(
            		'class' => 'TimeTMCoreBundle:Contact',
            		'property' => 'lastname',
            		'mapped' => false,
					'required' => false,
            		'empty_value' => 'Sélectionner les participants',
                    'label' => ' '
            ))
			->add('save' , 'submit')
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
            'data_class' => 'TimeTM\CoreBundle\Entity\Event'
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
