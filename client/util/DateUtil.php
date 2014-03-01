<?php

//
// Copyright (c) 2014 Oliver Lehmann <oliver@laladev.org>
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
// 1. Redistributions of source code must retain the above copyright
// notice, this list of conditions and the following disclaimer
// 2. Redistributions in binary form must reproduce the above copyright
// notice, this list of conditions and the following disclaimer in the
// documentation and/or other materials provided with the distribution.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
// ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
// ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
// FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
// DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
// OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
// LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
// OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
// SUCH DAMAGE.
//
// $Id: DateUtil.php,v 1.3 2014/03/01 00:48:59 olivleh1 Exp $
//
namespace client\util;

class DateUtil {
	private static $clientDateFormat;

	private static final function getClientDateFormat() {
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

	public static final function convertClientDateToTransport($clientDate) {
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

	public static final function convertTransportDateToClient($transportDate) {
		$format = self::getClientDateFormat();
		$clientDate = new \DateTime();
		$clientDate->setTimestamp( $transportDate );
		return $clientDate->format( $format );
	}
}

?>