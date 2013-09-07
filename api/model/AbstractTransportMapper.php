<?php

namespace rest\api\model;

abstract class AbstractTransportMapper {
	protected final function convertTransportDateToModel($ransportDate) {
		if($ransportDate) {
			$modelDate = new \DateTime();
			return $modelDate->setTimestamp( $ransportDate );
		} else {
			return NULL;
		}
	}

	protected final function convertModelDateToTransport($modelDate) {
		if ($modelDate != null) {
			return $modelDate->getTimestamp();
		} else {
			return NULL;
		}
	}
}

?>