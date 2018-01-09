<?php

namespace Crud\Action;

interface FilteringGateway {

	/**
	 * @return array
	 */
	public function filter($criteria);
}
