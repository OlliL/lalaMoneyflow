<?php

namespace rest\api\model\capitalsource;

use rest\api\model\capitalsource\transport\CapitalsourceTransport;

class getCapitalsourceByIdResponse {
	public $capitalsourceTransport;

	public final function getCapitalsourceTransport() {
		return $this->capitalsourceTransport;
	}

	public final function setCapitalsourceTransport(CapitalsourceTransport $capitalsourceTransport) {
		$this->capitalsourceTransport = $capitalsourceTransport;
	}
}

?>