<?php

namespace AppBundle\Doctrine;

use Crud\Gateway\CrudGatewayInterface;
use Doctrine\ORM\EntityRepository;

class CrudGateway extends EntityRepository implements CrudGatewayInterface {

	public function persist($object) {
		$this->getEntityManager()->persist($object);
		$this->getEntityManager()->flush();
	}

	public function filter($criteria) {
		
	}

}
