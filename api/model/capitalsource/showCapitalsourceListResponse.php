<?php

namespace rest\api\model\capitalsource;

class showCapitalsourceListResponse {
	public $capitalsourceTransport;
	public $initials;

	public final function getCapitalsourceTransport() {
		return $this->capitalsourceTransport;
	}

	public final function setCapitalsourceTransport(array $capitalsourceTransport) {
		$this->capitalsourceTransport = $capitalsourceTransport;
	}

	public final function getInitials() {
		return $this->initials;
	}

	public final function setInitials(array $initials) {
		$this->initials = $initials;
	}
}

?>