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
// $Id: ReportControllerHandler.php,v 1.9 2014/02/27 20:02:14 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;
use rest\api\model\report\showTrendsGraphRequest;
use rest\client\util\DateUtil;

class ReportControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
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
		}
		return self::$instance;
	}

	protected final function getCategory() {
		return 'report';
	}

	public final function listReports($year, $month) {
		$response = parent::getJson( 'listReports', array (
				$year,
				$month
		) );
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
		$response = parent::getJson( 'showTrendsForm' );
		if (is_array( $response )) {
			$showTrendsForm = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\report' );
			$result ['allYears'] = $showTrendsForm->getAllYears();
			if (is_array( $showTrendsForm->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $showTrendsForm->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
		}
		$result ['selected_capitalsources'] = $showTrendsForm->getSettingTrendCapitalsourceId();

		return $result;
	}

	public final function showTrendsGraph($capitalsourceIds, $startdate, $enddate) {
		$request = new showTrendsGraphRequest();
		$request->setCapitalsourceIds( $capitalsourceIds );
		$request->setStartDate( $startdate->format( 'U' ) );
		$request->setEndDate( $enddate->format( 'U' ) );

		$response = parent::putJson( 'showTrendsGraph', parent::json_encode_response( $request ) );
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