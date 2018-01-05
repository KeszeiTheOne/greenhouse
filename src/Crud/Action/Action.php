<?php

namespace Crud\Action;

interface Action {

	public function run($request);

	public function getResponder();
}
