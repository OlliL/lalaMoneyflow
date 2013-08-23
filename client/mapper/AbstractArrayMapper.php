<?php

namespace rest\client\mapper;

abstract class AbstractArrayMapper {

	private final function getClientDateFormat() {
		$patterns [0] = '/YYYY/';
		$patterns [1] = '/MM/';
		$patterns [2] = '/DD/';

		$replacements [0] = 'Y';
		$replacements [1] = 'm';
		$replacements [2] = 'd';

		$format = preg_replace( $patterns, $replacements, GUI_DATE_FORMAT );

		return $format;
	}

	protected final function convertClientDateToModel($clientDate) {
		if (empty( $clientDate ))
			return false;

		$format = self::getClientDateFormat();
		$parsedDate = date_parse_from_format( $format, $clientDate );

		if ($parsedDate ['warning_count'] > 0)
			return false;

		$modelDate = \DateTime::createFromFormat( $format, $clientDate );

		return $modelDate;
	}

	protected final function convertModelDateToClient($modelDate) {
		$format = self::getClientDateFormat();

		$clientDate = $modelDate->format( $format );

		return $clientDate;
	}
}

?>