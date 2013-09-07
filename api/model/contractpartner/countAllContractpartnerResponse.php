<?php

namespace rest\api\model\contractpartner;

class countAllContractpartnerResponse {
	public $count;

	public final function getCount() {
		return $this->count;
	}

	public final function setCount($count) {
		$this->count = $count;
	}
}

?>