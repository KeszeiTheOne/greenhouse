<?php

namespace Greenhouse\OktoController\AddOktoController;

use Crud\Action\Request;

class AddOktoControllerRequest implements Request {

	public $name;

	public function isValid() {
		return null !== $this->name;
	}

}
