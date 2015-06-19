<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * TimeTM Core bundle, extend FOSUserBundle
 * 
 * @author André Friedli <a@frian.org>
 */
class TimeTMUserBundle extends Bundle {

	/**
	 * define bundle as FOSUserBundle child
	 */
	public function getParent() {
		return 'FOSUserBundle';
	}
}
