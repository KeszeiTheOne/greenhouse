<?php

namespace Crud\Gateway;

use Crud\Action\FilteringGateway;

interface CrudGatewayInterface extends PersisterGateway, FilteringGateway {
	
}
