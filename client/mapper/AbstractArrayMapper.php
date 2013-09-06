<?php

namespace rest\client\mapper;

abstract class AbstractArrayMapper {
	private static $clientDateFormat;

	private final function getClientDateFormat() {
		if (! self::$clientDateFormat) {
			$patterns [0] = 'YYYY';
			$patterns [1] = 'MM';
			$patterns [2] = 'DD';

			$replacements [0] = 'Y';
			$replacements [1] = 'm';
			$replacements [2] = 'd';

			self::$clientDateFormat = str_replace( $patterns, $replacements, GUI_DATE_FORMAT );
		}
		return self::$clientDateFormat;
	}

	protected final function convertClientDateToModel($clientDate) {
		if (empty( $clientDate ))
			return false;

		$format = self::getClientDateFormat();
		$parsedDate = date_parse_from_format( $format, $clientDate );

		if ($parsedDate ['warning_count'] > 0)
			return false;

		$modelDate = \DateTime::createFromFormat( $format, $clientDate );
		if ($modelDate)
			$modelDate->setTime( 0, 0, 0 );

		return $modelDate;
	}

	protected final function convertModelDateToClient($modelDate) {
		$format = self::getClientDateFormat();

		$clientDate = $modelDate->format( $format );

		return $clientDate;
	}

	protected final function convertClientDateToJson($clientDate) {
		if (empty( $clientDate ))
			return null;

		$format = self::getClientDateFormat();
		$parsedDate = date_parse_from_format( $format, $clientDate );

		if ($parsedDate ['warning_count'] > 0)
			return null;

		$modelDate = \DateTime::createFromFormat( $format, $clientDate );
		if ($modelDate)
			$modelDate->setTime( 0, 0, 0 );

		return $modelDate->getTimestamp();
	}

	protected final function convertJsonDateToClient($jsonDate) {
		$format = self::getClientDateFormat();
		$clientDate = new \DateTime();
		$clientDate->setTimestamp( $jsonDate );
		return $clientDate->format( $format );
	}
}

?>