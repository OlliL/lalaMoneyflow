<?php

namespace rest\api\model\capitalsource;

class getAllCapitalsourcesByDateRangeResponse {
	public $capitalsourceTransport;

	public final function getCapitalsourceTransport() {
		return $this->capitalsourceTransport;
	}

	public final function setCapitalsourceTransport(array $capitalsourceTransport) {
		$this->capitalsourceTransport = $capitalsourceTransport;
	}
}

?>