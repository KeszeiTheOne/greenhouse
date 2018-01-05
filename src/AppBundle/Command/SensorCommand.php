<?php

namespace AppBundle\Command;

use Greenhouse\Model\OktoController;
use Greenhouse\OktoController\AddOktoController\AddOktoControllerAction;
use Greenhouse\OktoController\AddOktoController\AddOktoControllerRequest;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SensorCommand extends ContainerAwareCommand {

	protected function configure() {
		$this->setName("sensor");
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$repo = $this->getContainer()->get("doctrine")->getRepository(OktoController::class);

		$action = new AddOktoControllerAction("responder");
		$action->setOktoControllerGateway($repo);

		$request = new AddOktoControllerRequest();
		$request->name = "name";

		$action->run($request);
	}

}
