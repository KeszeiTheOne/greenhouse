<?php

namespace Crud\Action;

abstract class AbstractAction implements Action {

	private $responder;

	public function __construct($responder) {
		$this->responder = $responder;
	}

	public function getResponder() {
		return $this->responder;
	}

}
