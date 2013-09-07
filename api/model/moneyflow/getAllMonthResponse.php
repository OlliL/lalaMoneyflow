<?php

namespace rest\api\model\moneyflow;

class getAllMonthResponse {
	public $month;

	public final function getMonth() {
		return $this->month;
	}

	public final function setMonth(array $month) {
		$this->month = $month;
	}
}

?>