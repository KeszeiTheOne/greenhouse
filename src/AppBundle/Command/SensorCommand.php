<?php

namespace AppBundle\Command;

use Greenhouse\Model\Sensor;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SensorCommand extends ContainerAwareCommand {

	protected function configure() {
		$this->setName("sensor");
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$repo = $this->getContainer()->get("doctrine")->getRepository(Sensor::class);

		foreach ($repo->findAll() as $value) {
			$output->write($value->getLocation(), true);
		}
	}

}
