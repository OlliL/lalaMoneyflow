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
// $Id: MonthlySettlementControllerHandler.php,v 1.3 2014/02/09 19:14:01 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\client\util\CallServerUtil;
use rest\base\AbstractJsonSender;
use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;
use rest\api\model\monthlysettlement\upsertMonthlySettlementRequest;

class MonthlySettlementControllerHandler extends AbstractJsonSender {
	private static $instance;
	private static $callServer;

	protected function __construct() {
		parent::addMapper( 'rest\client\mapper\ArrayToMonthlySettlementTransportMapper', ClientArrayMapperEnum::MONTHLYSETTLEMENT_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new MonthlySettlementControllerHandler();
			self::$callServer = CallServerUtil::getInstance();
		}
		return self::$instance;
	}

	public final function showMonthlySettlementList($year, $month) {
		$url = URLPREFIX . SERVERPREFIX . 'monthlysettlement/showMonthlySettlementList/' . $year . '/' . $month . '/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$showMonthlySettlementList = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\monthlysettlement' );
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
		$url = URLPREFIX . SERVERPREFIX . 'monthlysettlement/showMonthlySettlementCreate/' . $year . '/' . $month . '/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$showMonthlySettlementCreate = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\monthlysettlement' );
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
		$url = URLPREFIX . SERVERPREFIX . 'monthlysettlement/showMonthlySettlementDelete/' . $year . '/' . $month . '/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$showMonthlySettlementDelete = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\monthlysettlement' );
			if (is_array( $showMonthlySettlementDelete->getMonthlySettlementTransport() )) {
				$result ['monthly_settlements'] = parent::mapArray( $showMonthlySettlementDelete->getMonthlySettlementTransport() );
			} else {
				$result ['monthly_settlements'] = '';
			}
		}

		return $result;
	}

	public final function upsertMonthlySettlement(array $monthlySettlement) {
		$url = URLPREFIX . SERVERPREFIX . 'monthlysettlement/upsertMonthlySettlement/' . self::$callServer->getSessionId();

		$monthlySettlementTransport = parent::mapArray( $monthlySettlement, ClientArrayMapperEnum::MONTHLYSETTLEMENT_TRANSPORT );

		$request = new upsertMonthlySettlementRequest();
		$request->setMonthlySettlementTransport( $monthlySettlementTransport );

		return self::$callServer->postJson( $url, parent::json_encode_response( $request ) );
	}

	public final function deleteMonthlySettlement($year, $month) {
		$url = URLPREFIX . SERVERPREFIX . 'monthlysettlement/deleteMonthlySettlement/' . $year . '/' . $month . '/' . self::$callServer->getSessionId();
		return self::$callServer->deleteJson( $url );
	}
}
?>