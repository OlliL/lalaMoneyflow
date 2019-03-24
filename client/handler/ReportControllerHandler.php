<?php
//
// Copyright (c) 2013-2017 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: ReportControllerHandler.php,v 1.22 2017/01/01 18:26:12 olivleh1 Exp $
//
namespace client\handler;

use api\model\report\showTrendsGraphRequest;
use api\model\report\listReportsResponse;
use api\model\report\showReportingFormResponse;
use api\model\report\showTrendsFormResponse;
use api\model\report\showTrendsGraphResponse;
use api\model\report\showYearlyReportGraphRequest;
use api\model\report\showMonthlyReportGraphRequest;
use api\model\report\showYearlyReportGraphResponse;
use api\model\report\showMonthlyReportGraphResponse;
use client\mapper\ArrayToCapitalsourceTransportMapper;
use client\mapper\ArrayToMoneyflowTransportMapper;
use client\mapper\ArrayToMoneyflowSplitEntryTransportMapper;
use client\mapper\ArrayToReportTurnoverCapitalsourceTransportMapper;
use client\mapper\ArrayToTrendsCalculatedTransportMapper;
use client\mapper\ArrayToTrendsSettledTransportMapper;
use client\mapper\ArrayToPostingAccountTransportMapper;
use client\mapper\ArrayToPostingAccountAmountTransportMapper;
use base\Singleton;

class ReportControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToCapitalsourceTransportMapper::getClass() );
		parent::addMapper( ArrayToMoneyflowTransportMapper::getClass() );
		parent::addMapper( ArrayToReportTurnoverCapitalsourceTransportMapper::getClass() );
		parent::addMapper( ArrayToTrendsCalculatedTransportMapper::getClass() );
		parent::addMapper( ArrayToTrendsSettledTransportMapper::getClass() );
		parent::addMapper( ArrayToPostingAccountTransportMapper::getClass() );
		parent::addMapper( ArrayToPostingAccountAmountTransportMapper::getClass() );
		parent::addMapper( ArrayToMoneyflowSplitEntryTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'report';
	}

	public final function listReports($year, $month) {
		$response = parent::getJson( 'listReports', array (
				$year,
				$month
		) );
		$result = null;
		if ($response instanceof listReportsResponse) {
			$result ['moneyflows'] = parent::mapArrayNullable( $response->getMoneyflowTransport() );
			$result ['moneyflow_split_entries'] = parent::mapArrayNullable( $response->getMoneyflowSplitEntryTransport() );
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
			$result ['moneyflows_with_receipt'] = $response->getMoneyflowsWithReceipt();
		}

		return $result;
	}

	public final function showReportingForm() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showReportingFormResponse) {
			$result ['allYears'] = $response->getAllYears();
			$result ['postingaccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
			$result ['accounts_no'] = $response->getPostingAccountIdsNo();
		}

		return $result;
	}

	public final function showTrendsForm() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showTrendsFormResponse) {
			$result ['minDate'] = $response->getMinDate();
			$result ['maxDate'] = $response->getMaxDate();
			$result ['capitalsources'] = parent::mapArrayNullable( $response->getCapitalsourceTransport() );
		}
		$result ['selected_capitalsources'] = $response->getSettingTrendCapitalsourceId();

		return $result;
	}

	public final function showTrendsGraph($capitalsourceIds, $startdate, $enddate) {
		$request = new showTrendsGraphRequest();
		$request->setCapitalsourceIds( $capitalsourceIds );
		$request->setStartDate( $startdate->format( \DateTime::ATOM ) );
		$request->setEndDate( $enddate->format( \DateTime::ATOM ) );

		$response = parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
		$result = null;
		if ($response instanceof showTrendsGraphResponse) {
			$result ['settled'] = parent::mapArrayNullable( $response->getTrendsSettledTransport() );
			$result ['calculated'] = parent::mapArrayNullable( $response->getTrendsCalculatedTransport() );
		}

		return $result;
	}

	public final function showYearlyReportGraph($postingAccountIdsYes, $postingAccountIdsNo, $startdate, $enddate) {
		$request = new showYearlyReportGraphRequest();
		$request->setPostingAccountIdsYes( $postingAccountIdsYes );
		$request->setPostingAccountIdsNo( $postingAccountIdsNo );
		$request->setStartDate( $startdate->format( \DateTime::ATOM ) );
		$request->setEndDate( $enddate->format( \DateTime::ATOM ) );

		$response = parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
		$result = null;
		if ($response instanceof showYearlyReportGraphResponse) {
			$result ['data'] = parent::mapArrayNullable( $response->getPostingAccountAmountTransport() );
			$result ['postingAccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
		}
		return $result;
	}

	public final function showMonthlyReportGraph($postingAccountIdsYes, $postingAccountIdsNo, $startdate, $enddate) {
		$request = new showMonthlyReportGraphRequest();
		$request->setPostingAccountIdsYes( $postingAccountIdsYes );
		$request->setPostingAccountIdsNo( $postingAccountIdsNo );
		$request->setStartDate( $startdate->format( \DateTime::ATOM ) );
		$request->setEndDate( $enddate->format( \DateTime::ATOM ) );

		$response = parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
		$result = null;
		if ($response instanceof showMonthlyReportGraphResponse) {
			$result ['data'] = parent::mapArrayNullable( $response->getPostingAccountAmountTransport() );
			$result ['postingAccounts'] = parent::mapArrayNullable( $response->getPostingAccountTransport() );
		}
		return $result;
	}
}

?>