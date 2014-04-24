<?php

namespace Lookin2\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Lookin2UserBundle extends Bundle {
	
	public function getParent() {
		
		return 'FOSUserBundle';
	}
}
