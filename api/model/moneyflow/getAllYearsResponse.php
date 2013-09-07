<?php

namespace rest\api\model\moneyflow;

class getAllYearsResponse {
	public $years;

	public final function getYears() {
		return $this->years;
	}

	public final function setYears(array $years) {
		$this->years = $years;
	}
}

?>