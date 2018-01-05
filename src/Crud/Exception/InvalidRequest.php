<?php

namespace Crud\Exception;

use RuntimeException;
use Throwable;

class InvalidRequest extends RuntimeException {

	private $request;

	public function __construct($request, string $message = "", int $code = 0, Throwable $previous = null) {
		$this->request = $request;
		parent::__construct($message, $code, $previous);
	}

	public function getRequest() {
		return $this->request;
	}

}
