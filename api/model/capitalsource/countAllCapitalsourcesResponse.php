<?php

namespace rest\api\model\capitalsource;

class countAllCapitalsourcesResponse {
	public $count;

	public final function getCount() {
		return $this->count;
	}

	public final function setCount($count) {
		$this->count = $count;
	}
}

?>