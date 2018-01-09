<?php

namespace Crud\Action;

interface Action {

	public function run(Request $request);

	public function getResponder();
}
