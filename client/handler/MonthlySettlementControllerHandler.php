<?php
//
// Copyright (c) 2013-2015 Oliver Lehmann <oliver@laladev.org>
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
// $Id: MonthlySettlementControllerHandler.php,v 1.11 2015/02/13 00:03:38 olivleh1 Exp $
//
namespace client\handler;

use base\JsonAutoMapper;
use api\model\monthlysettlement\upsertMonthlySettlementRequest;
use api\model\monthlysettlement\showMonthlySettlementListResponse;
use api\model\monthlysettlement\showMonthlySettlementCreateResponse;
use api\model\monthlysettlement\showMonthlySettlementDeleteResponse;
use client\mapper\ArrayToMonthlySettlementTransportMapper;
use api\model\transport\MonthlySettlementTransport;

class MonthlySettlementControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( ArrayToMonthlySettlementTransportMapper::getClass() );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new MonthlySettlementControllerHandler();
		}
		return self::$instance;
	}

	protected final function getCategory() {
		return 'monthlysettlement';
	}

	public final function showMonthlySettlementList($year, $month) {
		$response = parent::getJson( __FUNCTION__, array (
				$year,
				$month
		) );
		$result = null;
		if ($response instanceof showMonthlySettlementListResponse) {
			$result ['monthly_settlements'] = parent::mapArrayNullable( $response->getMonthlySettlementTransport() );
			$result ['allYears'] = $response->getAllYears();
			$result ['allMonth'] = $response->getAllMonth();
			$result ['year'] = $response->getYear();
			$result ['month'] = $response->getMonth();
			$result ['numberOfEditableSettlements'] = $response->getNumberOfEditableSettlements();
			$result ['numberOfAddableSettlements'] = $response->getNumberOfAddableSettlements();
		}

		return $result;
	}

	public final function showMonthlySettlementCreate($year, $month) {
		$response = parent::getJson( __FUNCTION__, array (
				$year,
				$month
		) );
		$result = null;
		if ($response instanceof showMonthlySettlementCreateResponse) {
			$result ['monthly_settlements'] = parent::mapArrayNullable( $response->getMonthlySettlementTransport() );
			$result ['year'] = $response->getYear();
			$result ['month'] = $response->getMonth();
			$result ['edit_mode'] = $response->getEditMode();
		}

		return $result;
	}

	public final function showMonthlySettlementDelete($year, $month) {
		$response = parent::getJson( __FUNCTION__, array (
				$year,
				$month
		) );
		$result = null;
		if ($response instanceof showMonthlySettlementDeleteResponse) {
			$result ['monthly_settlements'] = parent::mapArrayNullable( $response->getMonthlySettlementTransport() );
		}

		return $result;
	}

	public final function upsertMonthlySettlement(array $monthlySettlement) {
		$monthlySettlementTransport = parent::mapArray( $monthlySettlement, MonthlySettlementTransport::getClass() );

		$request = new upsertMonthlySettlementRequest();
		$request->setMonthlySettlementTransport( $monthlySettlementTransport );

		return parent::postJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function deleteMonthlySettlement($year, $month) {
		return parent::deleteJson( __FUNCTION__, array (
				$year,
				$month
		) );
	}
}
?>