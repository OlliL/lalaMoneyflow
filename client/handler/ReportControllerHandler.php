<?php
//
// Copyright (c) 2013-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: ReportControllerHandler.php,v 1.6 2014/02/08 01:38:15 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\client\util\CallServerUtil;
use rest\base\AbstractJsonSender;
use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;
use rest\api\model\report\showTrendsGraphRequest;
use rest\client\util\DateUtil;

class ReportControllerHandler extends AbstractJsonSender {
	private static $instance;
	private static $callServer;

	protected function __construct() {
		parent::addMapper( 'rest\client\mapper\ArrayToValidationItemTransportMapper', ClientArrayMapperEnum::VALIDATIONITEM_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToCapitalsourceTransportMapper', ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToMoneyflowTransportMapper', ClientArrayMapperEnum::MONEYFLOW_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToReportTurnoverCapitalsourceTransportMapper', ClientArrayMapperEnum::REPORTTURNOVERCAPITALSOURCE_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToTrendsCalculatedTransportMapper', ClientArrayMapperEnum::TRENDSCALCULATED_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToTrendsSettledTransportMapper', ClientArrayMapperEnum::TRENDSSETTLED_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new ReportControllerHandler();
			self::$callServer = CallServerUtil::getInstance();
		}
		return self::$instance;
	}

	public final function listReports($year, $month) {
		$url = URLPREFIX . SERVERPREFIX . 'report/listReports/' . $year . '/' . $month . '/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$listReports = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\report' );
			if (is_array( $listReports->getMoneyflowTransport() )) {
				$result ['moneyflows'] = parent::mapArray( $listReports->getMoneyflowTransport() );
			} else {
				$result ['moneyflows'] = '';
			}
			if (is_array( $listReports->getReportTurnoverCapitalsourceTransport() )) {
				$result ['turnover_capitalsources'] = parent::mapArray( $listReports->getReportTurnoverCapitalsourceTransport() );
			} else {
				$result ['turnover_capitalsources'] = '';
			}
			$result ['allYears'] = $listReports->getAllYears();
			$result ['allMonth'] = $listReports->getAllMonth();
			$result ['year'] = $listReports->getYear();
			$result ['month'] = $listReports->getMonth();
			$result ['firstamount'] = $listReports->getAmountBeginOfYear();
			$result ['calculated_yearly_turnover'] = $listReports->getTurnoverEndOfYearCalculated();
			$result ['prev_link'] = $listReports->getPreviousMonthHasMoneyflows();
			$result ['next_link'] = $listReports->getNextMonthHasMoneyflows();
			$result ['prev_month'] = $listReports->getPreviousMonth();
			$result ['prev_year'] = $listReports->getPreviousYear();
			$result ['next_month'] = $listReports->getNextMonth();
			$result ['next_year'] = $listReports->getNextYear();
		}

		return $result;
	}

	public final function showTrendsForm() {
		$url = URLPREFIX . SERVERPREFIX . 'report/showTrendsForm/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$showTrendsForm = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\report' );
			$result ['allYears'] = $showTrendsForm->getAllYears();
			if (is_array( $showTrendsForm->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $showTrendsForm->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
		}

		return $result;
	}

	public final function showTrendsGraph($capitalsourceIds, $startdate, $enddate) {
		$url = URLPREFIX . SERVERPREFIX . 'report/showTrendsGraph/' . self::$callServer->getSessionId();

		$request = new showTrendsGraphRequest();
		$request->setCapitalsourceIds( $capitalsourceIds );
		$request->setStartDate( $startdate->format( 'U' ) );
		$request->setEndDate( $enddate->format( 'U' ) );

		$response = self::$callServer->putJson( $url, parent::json_encode_response( $request ) );
		if (is_array( $response )) {
			$showTrendsGraphResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\report' );
			if (is_array( $showTrendsGraphResponse->getTrendsSettledTransport() )) {
				$result ['settled'] = parent::mapArray( $showTrendsGraphResponse->getTrendsSettledTransport() );
			} else {
				$result ['settled'] = array ();
			}
			if (is_array( $showTrendsGraphResponse->getTrendsCalculatedTransport() )) {
				$result ['calculated'] = parent::mapArray( $showTrendsGraphResponse->getTrendsCalculatedTransport() );
			} else {
				$result ['calculated'] = array ();
			}
		}

		return $result;
	}
}

?>