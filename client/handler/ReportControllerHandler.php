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
// $Id: ReportControllerHandler.php,v 1.12 2014/03/07 20:41:36 olivleh1 Exp $
//
namespace client\handler;

use client\mapper\ClientArrayMapperEnum;
use base\JsonAutoMapper;
use api\model\report\showTrendsGraphRequest;
use client\util\DateUtil;
use api\model\report\listReportsResponse;
use api\model\report\showReportingFormResponse;
use api\model\report\showTrendsFormResponse;
use api\model\report\showTrendsGraphResponse;

class ReportControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( 'client\mapper\ArrayToValidationItemTransportMapper', ClientArrayMapperEnum::VALIDATIONITEM_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToCapitalsourceTransportMapper', ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToMoneyflowTransportMapper', ClientArrayMapperEnum::MONEYFLOW_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToReportTurnoverCapitalsourceTransportMapper', ClientArrayMapperEnum::REPORTTURNOVERCAPITALSOURCE_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToTrendsCalculatedTransportMapper', ClientArrayMapperEnum::TRENDSCALCULATED_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToTrendsSettledTransportMapper', ClientArrayMapperEnum::TRENDSSETTLED_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToPostingAccountTransportMapper', ClientArrayMapperEnum::POSTINGACCOUNT_TRANSPORT );
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
		if ($response instanceof listReportsResponse) {
			$result ['moneyflows'] = parent::mapArrayNullable( $response->getMoneyflowTransport() );
			$result ['turnover_capitalsources'] = parent::mapArrayNullable( $response->getReportTurnoverCapitalsourceTransport() );
			$result ['allYears'] = $response->getAllYears();
			$result ['allMonth'] = $response->getAllMonth();
			$result ['year'] = $response->getYear();
			$result ['month'] = $response->getMonth();
			$result ['firstamount'] = $response->getAmountBeginOfYear();
			$result ['calculated_yearly_turnover'] = $response->getTurnoverEndOfYearCalculated();
			$result ['prev_link'] = $response->getPreviousMonthHasMoneyflows();
			$result ['next_link'] = $response->getNextMonthHasMoneyflows();
			$result ['prev_month'] = $response->getPreviousMonth();
			$result ['prev_year'] = $response->getPreviousYear();
			$result ['next_month'] = $response->getNextMonth();
			$result ['next_year'] = $response->getNextYear();
		}

		return $result;
	}

	public final function showReportingForm() {
		$response = parent::getJson( __FUNCTION__ );
		if ($response instanceof showReportingFormResponse) {
			$result ['allYears'] = $response->getAllYears();
			$result ['postingaccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
		}

		return $result;
	}

	public final function showTrendsForm() {
		$response = parent::getJson( __FUNCTION__ );
		if ($response instanceof showTrendsFormResponse) {
			$result ['allYears'] = $response->getAllYears();
			$result ['capitalsources'] = parent::mapArrayNullable( $response->getCapitalsourceTransport() );
		}
		$result ['selected_capitalsources'] = $response->getSettingTrendCapitalsourceId();

		return $result;
	}

	public final function showTrendsGraph($capitalsourceIds, $startdate, $enddate) {
		$request = new showTrendsGraphRequest();
		$request->setCapitalsourceIds( $capitalsourceIds );
		$request->setStartDate( $startdate->format( 'U' ) );
		$request->setEndDate( $enddate->format( 'U' ) );

		$response = parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
		if ($response instanceof showTrendsGraphResponse) {
			$result ['settled'] = parent::mapArrayNullable( $response->getTrendsSettledTransport() );
			$result ['calculated'] = parent::mapArrayNullable( $response->getTrendsCalculatedTransport() );
		}

		return $result;
	}
}

?>