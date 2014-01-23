<?php

namespace rest\api\model\capitalsource;

use rest\api\model\transport\CapitalsourceTransport;

class createCapitalsourceRequest {
	public $capitalsourceTransport;

	public final function getCapitalsourceTransport() {
		return $this->capitalsourceTransport;
	}

	public final function setCapitalsourceTransport(CapitalsourceTransport $capitalsourceTransport) {
		$this->capitalsourceTransport = $capitalsourceTransport;
	}
}

?>