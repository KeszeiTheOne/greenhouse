<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class SmartHomeController extends FOSRestController {

	public function calendarSampleAction(Request $httpRequest, KernelInterface $kernel) {
		$application = new Application($kernel);
		$application->setAutoExit(false);

		$input = new ArrayInput([
			'command' => 'sensor',
		]);

		// You can use NullOutput() if you don't need the output
		$output = new BufferedOutput();
		$application->run($input, $output);

		// return the output, don't use if you used NullOutput()
		$content = $output->fetch();

		// return new Response(""), if you used NullOutput()
		return new Response($content);
	}

}
