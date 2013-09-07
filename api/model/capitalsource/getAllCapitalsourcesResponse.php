<?php

namespace rest\api\model\capitalsource;

use rest\api\model\capitalsource\transport\CapitalsourceTransport;

class getAllCapitalsourcesResponse {
	public $capitalsourceTransport;

	public final function getCapitalsourceTransport() {
		return $this->capitalsourceTransport;
	}

	public final function setCapitalsourceTransport(array $capitalsourceTransport) {
		$this->capitalsourceTransport = $capitalsourceTransport;
	}
}

?>