<?php

namespace Greenhouse\OktoController\AddOktoController;

class AddOktoControllerRequest {

	public $name;

	public function isValid() {
		return null !== $this->name;
	}

}
