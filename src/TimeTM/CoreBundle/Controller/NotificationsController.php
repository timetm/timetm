<?php
/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
/**
 * Notifications controller.
 *
 * @Route("/notifications")
 *
 * @author André Friedli <a@frian.org>
 */
class NotificationsController extends Controller
{
	/**
	 * Send daily email notification with next events
	 *
	 * @return Response
	 *
	 * @Route("/daily/events/{secret}", name="daily_events")
	 * @Method("GET")
	 */
	public function dailyEventsAction($secret)
	{
		$secretParam = $this->container->getParameter('timetm.notification.secret');
		
		if ($secret !== $secretParam) {
			return new Response('error');
		}

		$kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
			'command' => 'ttm:event:notifications',
			'--force' => 1,
        	'--web' => 1,
        ));

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();

        // return new Response(""), if you used NullOutput()
        return new Response($content);
	}
}
