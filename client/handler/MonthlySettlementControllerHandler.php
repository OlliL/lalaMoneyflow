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
// $Id: MonthlySettlementControllerHandler.php,v 1.6 2014/02/28 22:19:47 olivleh1 Exp $
//
namespace client\handler;

use client\mapper\ClientArrayMapperEnum;
use base\JsonAutoMapper;
use api\model\monthlysettlement\upsertMonthlySettlementRequest;

class MonthlySettlementControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( 'client\mapper\ArrayToMonthlySettlementTransportMapper', ClientArrayMapperEnum::MONTHLYSETTLEMENT_TRANSPORT );
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
		$response = parent::getJson( 'showMonthlySettlementList', array (
				$year,
				$month
		) );
		if (is_array( $response )) {
			$showMonthlySettlementList = JsonAutoMapper::mapAToB( $response, '\\api\\model\\monthlysettlement' );
			if (is_array( $showMonthlySettlementList->getMonthlySettlementTransport() )) {
				$result ['monthly_settlements'] = parent::mapArray( $showMonthlySettlementList->getMonthlySettlementTransport() );
			} else {
				$result ['monthly_settlements'] = '';
			}
			$result ['allYears'] = $showMonthlySettlementList->getAllYears();
			$result ['allMonth'] = $showMonthlySettlementList->getAllMonth();
			$result ['year'] = $showMonthlySettlementList->getYear();
			$result ['month'] = $showMonthlySettlementList->getMonth();
			$result ['numberOfEditableSettlements'] = $showMonthlySettlementList->getNumberOfEditableSettlements();
			$result ['numberOfAddableSettlements'] = $showMonthlySettlementList->getNumberOfAddableSettlements();
		}

		return $result;
	}

	public final function showMonthlySettlementCreate($year, $month) {
		$response = parent::getJson( 'showMonthlySettlementCreate', array (
				$year,
				$month
		) );
		if (is_array( $response )) {
			$showMonthlySettlementCreate = JsonAutoMapper::mapAToB( $response, '\\api\\model\\monthlysettlement' );
			if (is_array( $showMonthlySettlementCreate->getMonthlySettlementTransport() )) {
				$result ['monthly_settlements'] = parent::mapArray( $showMonthlySettlementCreate->getMonthlySettlementTransport() );
			} else {
				$result ['monthly_settlements'] = '';
			}
			$result ['year'] = $showMonthlySettlementCreate->getYear();
			$result ['month'] = $showMonthlySettlementCreate->getMonth();
			$result ['edit_mode'] = $showMonthlySettlementCreate->getEditMode();
		}

		return $result;
	}

	public final function showMonthlySettlementDelete($year, $month) {
		$response = parent::getJson( 'showMonthlySettlementDelete', array (
				$year,
				$month
		) );
		if (is_array( $response )) {
			$showMonthlySettlementDelete = JsonAutoMapper::mapAToB( $response, '\\api\\model\\monthlysettlement' );
			if (is_array( $showMonthlySettlementDelete->getMonthlySettlementTransport() )) {
				$result ['monthly_settlements'] = parent::mapArray( $showMonthlySettlementDelete->getMonthlySettlementTransport() );
			} else {
				$result ['monthly_settlements'] = '';
			}
		}

		return $result;
	}

	public final function upsertMonthlySettlement(array $monthlySettlement) {
		$monthlySettlementTransport = parent::mapArray( $monthlySettlement, ClientArrayMapperEnum::MONTHLYSETTLEMENT_TRANSPORT );

		$request = new upsertMonthlySettlementRequest();
		$request->setMonthlySettlementTransport( $monthlySettlementTransport );

		return parent::postJson( 'upsertMonthlySettlement', parent::json_encode_response( $request ) );
	}

	public final function deleteMonthlySettlement($year, $month) {
		return parent::deleteJson( 'deleteMonthlySettlement', array (
				$year,
				$month
		) );
	}
}
?>