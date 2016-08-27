<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Model;

use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Class representing a monthly calendar
 *
 * @author André Friedli <a@frian.org>
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
	public function __construct(Router $router, TranslatorInterface $translator, $calendarHelper) {
		parent::__construct($router, $translator);
        $this->calendarHelper = $calendarHelper;
	}

	/**
	 * Set additionnal panel navigation parameters
	 */
	public function setAdditionnalNavigationParameters() {}

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
		$resolver = new OptionsResolver();
		$this->configureOptions($resolver);

		try {
			$this->options = $resolver->resolve($options);
		} catch(\Exception $e) {

			$msg = $e->getMessage ();

            if (preg_match('/option\s+\"(\w+)\".*NULL/', $msg, $matches)) {

                $options['year'] = date('Y');
                $options['month'] = date('m');
            }
            else {
                throw new NotFoundHttpException("Page not found");
            }

		}

        $this->calendarHelper->checkInputDate($options['year'], $options['month'], '01');

        // checkdate($options['month'], '01', '01', $options['year']);

		$this->setYear($options['year']);
		$this->setMonth($options['month']);

		/* if we are in current month, set day to current day */
		$_day = 1;

		if (date('m') == $this->getMonth() && date('Y') == $this->getYear()) {
			$_day = date('d');
		}
		$this->setWeekno(date('W', mktime(0, 0, 0, $this->getMonth(), $_day, $this->getYear())));
	}

	/**
	 * configure the options resolver.
	 *
	 * - required : year, month
	 * - optionnal : type
	 * - allowed types : year, month => numeric
	 */
	protected function configureOptions(OptionsResolver $resolver) {
		$resolver->setRequired (array(
			'year',
			'month'
		));
		$resolver->setDefined(array(
			'type'
		));
		$resolver->setAllowedTypes('year', array('numeric'));
		$resolver->setAllowedTypes('month', array('numeric'));

		$resolver->setAllowedValues('type', array(null, 'panel', 'control'));
	}

}
