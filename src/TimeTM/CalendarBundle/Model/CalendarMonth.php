<?php

/**
 * This file is part of TimeTM
 *
 * @author André andre@at-info.ch
 */

// src/TimeTM/CalendarBundle/Model/CalendarMonth.php
namespace TimeTM\CalendarBundle\Model;

use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * class representing a monthly calendar
 */
class CalendarMonth extends Calendar {
	
	/**
	 * the router service
	 *
	 * @var \Symfony\Component\Routing\Router
	 */
	protected $router;
	
	/**
	 * the translator service
	 *
	 * @var \Symfony\Component\Translation\Translator
	 */
	protected $translator;
	
	/**
	 * the options resolver
	 *
	 * @var Symfony\Component\OptionsResolver\OptionsResolver
	 */
	private $resolver;
	
	/**
	 * options
	 *
	 * @var array
	 */
	private $options;
	
	/*
	 * -- public ----------------------------------------------------------------
	 */
	
	/**
	 * Constructor.
	 *
	 * @param service $router
	 *        	The router service
	 * @param service $translator
	 *        	The translation service
	 */
	public function __construct(Router $router, TranslatorInterface $translator) {
		parent::__construct ( $router, $translator );
	}
	
	/**
	 * Set additionnal panel navigation parameters
	 */
	public function setAdditionnalNavigationParameters() {
		// dummy;
	}
	
	/*
	 * -- protected -------------------------------------------------------------
	 */
	
	/**
	 * initialize the calendar.
	 *
	 * set :
	 *
	 * - month
	 * - monthName
	 *
	 * extends Calender::init
	 * 
	 * @see Calender::init() The extended function
	 *     
	 * @param mixed $param        	
	 */
	protected function childInit(array $options = array()) {
		
		// handle parameters
		$resolver = new OptionsResolver ();
		$this->setDefaultOptions ( $resolver );
		
		try {
			$this->options = $resolver->resolve ( $options );
		} catch ( \Exception $e ) {
			
			$msg = $e->getMessage ();
			
			preg_match ( '/option\s+\"(\w+)\"/', $msg, $matches );
			$param = $matches [1];
			
			switch ($param) {
				case 'year' :
					$options ['year'] = date ( 'Y' );
					break;
				case 'month' :
					$options ['month'] = date ( 'm' );
					break;
			}
		}
		
		$this->setYear ( $options ['year'] );
		$this->setMonth ( $options ['month'] );
		
		/* if we are in current month, set day to current day */
		$_day = 1;
		
		if (date ( 'm' ) == $this->getMonth () && date ( 'Y' ) == $this->getYear ()) {
			$_day = date ( 'd' );
		}
		$this->setWeekno ( date ( 'W', mktime ( 0, 0, 0, $this->getMonth (), $_day, $this->getYear () ) ) );
	}
	
	/**
	 * configure the options resolver.
	 *
	 * - required : year, month
	 * - optionnal : type
	 * - allowed types : year, month => null, numeric
	 */
	protected function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setRequired ( array (
			'year',
			'month' 
		));
		$resolver->setOptional ( array (
			'type' 
		));
		$resolver->setAllowedTypes ( array (
			'year' => array (
				'null',
				'numeric' 
			),
			'month' => array (
				'null',
				'numeric' 
			) 
		));
		
		$resolver->setAllowedValues ( array (
			'type' => array (
				'panel',
				'control' 
			) 
		));
	}
}
