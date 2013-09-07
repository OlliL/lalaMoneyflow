<?php

namespace rest\api\model\predefmoneyflow;

class countAllPreDefMoneyflowsResponse {
	public $count;

	public final function getCount() {
		return $this->count;
	}

	public final function setCount($count) {
		$this->count = $count;
	}
}

?>