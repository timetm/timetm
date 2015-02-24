<?php

namespace TimeTM\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TimeTMUserBundle extends Bundle {
	
	public function getParent() {
		
		return 'FOSUserBundle';
	}
}
