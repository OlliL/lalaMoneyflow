<?php

//
// Copyright (c) 2013 Oliver Lehmann <oliver@laladev.org>
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
// $Id: CallServer.php,v 1.14 2013/09/07 22:10:18 olivleh1 Exp $
//
namespace rest\client;

use \Httpful\Request;
use \Httpful\Httpful;
use \Httpful\Mime;
use \Httpful\Handlers\JsonHandler;
use rest\base\AbstractJsonSender;
use rest\model\Capitalsource;
use rest\model\Contractpartner;
use rest\model\Moneyflow;
use rest\model\mapper\JsonArrayMapperEnum;
use rest\client\mapper\ClientArrayMapperEnum;
use rest\model\mapper\JsonAutoMapper;
use rest\api\model\capitalsource\createCapitalsourceRequest;
use rest\api\model\capitalsource\updateCapitalsourceRequest;
use rest\api\model\contractpartner\createContractpartnerRequest;
use rest\api\model\contractpartner\updateContractpartnerRequest;
use rest\api\model\moneyflow\updateMoneyflowRequest;

class CallServer extends AbstractJsonSender {
	private $sessionId;
	private static $instance;

	private function __construct() {
		parent::addMapper( 'rest\model\mapper\JsonToCapitalsourceMapper', JsonArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE );
		parent::addMapper( 'rest\model\mapper\JsonToContractpartnerMapper', JsonArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE );
		parent::addMapper( 'rest\model\mapper\JsonToMoneyflowMapper', JsonArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );
		parent::addMapper( 'rest\model\mapper\JsonToUserMapper', JsonArrayMapperEnum::USER_ARRAY_TYPE );
		parent::addMapper( 'rest\model\mapper\JsonToSessionMapper', JsonArrayMapperEnum::SESSION_ARRAY_TYPE );
		parent::addMapper( 'rest\model\mapper\JsonToPreDefMoneyflowMapper', JsonArrayMapperEnum::PREDEFMONEYFLOW_ARRAY_TYPE );
		parent::addMapper( 'rest\model\mapper\validation\JsonToValidationResultMapper', JsonArrayMapperEnum::VALIDATION_RESULT_ARRAY_TYPE );

		parent::addMapper( 'rest\server\controller\mapper\CapitalsourceTransportToCapitalsourceMapper', NULL, JsonArrayMapperEnum::CAPITALSOURCE_TRANSPORT );
		parent::addMapper( 'rest\server\controller\mapper\ContractpartnerTransportToContractpartnerMapper', NULL, JsonArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );
		parent::addMapper( 'rest\server\controller\mapper\MoneyflowTransportToMoneyflowMapper', NULL, JsonArrayMapperEnum::MONEYFLOW_TRANSPORT );
		parent::addMapper( 'rest\server\controller\mapper\PreDefMoneyflowTransportToPreDefMoneyflowMapper', NULL, JsonArrayMapperEnum::PREDEFMONEYFLOW_TRANSPORT );

		parent::addMapper( 'rest\client\mapper\ArrayToCapitalsourceMapper', ClientArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE, 'Capitalsource' );
		parent::addMapper( 'rest\client\mapper\ArrayToContractpartnerMapper', ClientArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE, 'Contractpartner' );
		parent::addMapper( 'rest\client\mapper\ArrayToMoneyflowMapper', ClientArrayMapperEnum::MONEYFLOW_ARRAY_TYPE, 'Moneyflow' );
		parent::addMapper( 'rest\client\mapper\ArrayToPreDefMoneyflowMapper', ClientArrayMapperEnum::PREDEFMONEYFLOW_ARRAY_TYPE, 'PreDefMoneyflow' );

		// parent::addMapper( 'rest\client\mapper\JsonCapitalsourceTransportToArrayMapper', JsonArrayMapperEnum::CAPITALSOURCE_TRANSPORT, ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );
		Httpful::register( Mime::JSON, new JsonHandler( array (
				'decode_as_array' => true
		) ) );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new CallServer();
		}
		return self::$instance;
	}

	public function setSessionId($sessionId) {
		$this->sessionId = $sessionId;
	}

	private final function handle_result($result) {
		if (! is_array( $result )) {
			echo '<font color="red"><u>Server Error occured</u><pre>' . $result . '</pre></font><br>';
			add_error( 204 );
			return false;
		} else if (array_key_exists( 'ValidationResult', $result )) {
			return parent::map( $result ['ValidationResult'], JsonArrayMapperEnum::VALIDATION_RESULT_ARRAY_TYPE );
		} else if (array_key_exists( 'error', $result )) {
			if ($result ['error'] ['code'] < 0) {
				echo '<font color="red"><u>Server Error occured</u><pre>' . $result ['error'] ['message'] . '</pre></font><br>';
				add_error( 204 );
			}
			add_error( $result ['error'] ['code'] );
			return false;
		}
		return $result;
	}

	private final function getJson($url) {
		$response = Request::get( $url )->withoutStrictSsl()->addOnCurlOption( CURLOPT_ENCODING, 'compress, deflate, gzip' )->send();
		if ($response->code == 204) {
			return false;
		} else {
			return self::handle_result( $response->body );
		}

		// $ch = curl_init();
		// curl_setopt( $ch, CURLOPT_URL, $url );
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_ENCODING, 'compress, deflate, gzip');
		// $result = curl_exec( $ch );
		// $ret = self::handle_result( json_decode( $result, true ));
		// curl_close( $ch );
		// return $ret;
	}

	// create
	private final function postJson($url, $json) {
		$response = Request::post( $url )->withoutStrictSsl()->sendsJson()->body( $json )->send();
		if ($response->code == 204) {
			return true;
		} else {
			var_dump( $response->body );
			return self::handle_result( $response->body );
		}
	}

	// update
	private final function putJson($url, $json) {
		$response = Request::put( $url )->withoutStrictSsl()->sendsJson()->body( $json )->send();
		if ($response->code == 204) {
			return true;
		} else {
			return self::handle_result( $response->body );
		}
	}

	private final function deleteJson($url) {
		$response = Request::delete( $url )->withoutStrictSsl()->send();
		if ($response->code == 204) {
			return true;
		} else {
			return self::handle_result( $response->body );
		}
	}

	/*
	 * SessionService
	 */
	public final function doLogon($user, $password) {
		$url = URLPREFIX . SERVERPREFIX . 'sessionService/logon/' . $user . '/' . $password;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = parent::map( $jsonArray, JsonArrayMapperEnum::SESSION_ARRAY_TYPE );
			self::setSessionId( $result->getId() );
		}
		return $result;
		// return reset( $session );
	}

	/*
	 * UserService
	 */
	public final function getUserById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'userService/getUserById/' . $id . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = parent::map( $jsonArray, JsonArrayMapperEnum::USER_ARRAY_TYPE );
		}
		return $result;
	}

	/*
	 * MoneyflowService
	 */
	public final function getMoneyflowById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getMoneyflowById/' . $id . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getMoneyflowByIdResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\moneyflow' );
			$result = parent::map( $getMoneyflowByIdResponse->getMoneyflowTransport() );
			$result = parent::map( $result, 'Moneyflow' );
		}
		return $result;
	}

	public final function getMoneyflowsByMonth($year, $month) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getMoneyflowsByMonth/' . $year . '/' . $month . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getMoneyflowsByMonthResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\moneyflow' );
			if (is_array( $getMoneyflowsByMonthResponse->getMoneyflowTransport() )) {
				$result = parent::mapArray( $getMoneyflowsByMonthResponse->getMoneyflowTransport() );
				$result = parent::mapArray( $result, 'Moneyflow' );
			} else {
				$result = '';
			}
		}
		return $result;
	}

	public final function getAllMoneyflowYears() {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getAllYears/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getAllYearsResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\moneyflow' );
			$result = $getAllYearsResponse->getYears();
		}
		return $result;
	}

	public final function getAllMoneyflowMonth($year) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getAllMonth/' . $year . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getAllMonthResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\moneyflow' );
			$result = $getAllMonthResponse->getMonth();
		}
		return $result;
	}

	public final function createMoneyflows(array $moneyflows) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/createMoneyflows/' . $this->sessionId;

		$preDefMoneyflowIds = array ();

		foreach ( $moneyflows as $moneyflow ) {
			if ($moneyflow ['predefmoneyflowid'] > 0) {
				$preDefMoneyflowIds [] = $moneyflow ['predefmoneyflowid'];
			}
		}
		$moneyflows = parent::mapArray( $moneyflows, ClientArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );
		$moneyflowTransport = parent::mapArray( $moneyflows, JsonArrayMapperEnum::MONEYFLOW_TRANSPORT );

		$request = new createMoneyflowsRequest();
		$request->setMoneyflowTransport( $moneyflowTransport );
		$request->setUsedPreDefMoneyflowIds( $preDefMoneyflowIds );

		return self::postJson( $url, parent::json_encode_response( $request ) );
	}

	public final function updateMoneyflow(array $moneyflow) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/updateMoneyflow/' . $this->sessionId;
		$moneyflow = parent::map( $moneyflow, ClientArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );
		$moneyflowTransport = parent::map( $moneyflow, JsonArrayMapperEnum::MONEYFLOW_TRANSPORT );

		$request = new updateMoneyflowRequest();
		$request->setMoneyflowTransport( $moneyflowTransport );
		return self::putJson( $url, parent::json_encode_response( $request ) );
	}

	public final function deleteMoneyflow($id) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/deleteMoneyflowById/' . $id . '/' . $this->sessionId;
		return self::deleteJson( $url );
	}
	/*
	 * CapitalsourceService
	 */
	public final function getAllCapitalsources() {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllCapitalsources/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getAllCapitalsourcesResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\capitalsource' );
			if (is_array( $getAllCapitalsourcesResponse->getCapitalsourceTransport() )) {
				$result = parent::mapArray( $getAllCapitalsourcesResponse->getCapitalsourceTransport() );
				$result = parent::mapArray( $result, 'Capitalsource' );
			} else {
				$result = '';
			}
		}

		return $result;
	}

	public final function getAllCapitalsourcesByDateRange($validfrom, $validtil) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllCapitalsourcesByDateRange/' . $validfrom . '/' . $validtil . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getAllCapitalsourcesByDateRangeResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\capitalsource' );
			if (is_array( $getAllCapitalsourcesByDateRangeResponse->getCapitalsourceTransport() )) {
				$result = parent::mapArray( $getAllCapitalsourcesByDateRangeResponse->getCapitalsourceTransport() );
				$result = parent::mapArray( $result, 'Capitalsource' );
			} else {
				$result = '';
			}
		}
		return $result;
	}

	public final function getAllCapitalsourcesByInitial($initial) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllCapitalsourcesByInitial/' . $initial . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getAllCapitalsourcesByInitialResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\capitalsource' );
			if (is_array( $getAllCapitalsourcesByInitialResponse->getCapitalsourceTransport() )) {
				$result = parent::mapArray( $getAllCapitalsourcesByInitialResponse->getCapitalsourceTransport() );
				$result = parent::mapArray( $result, 'Capitalsource' );
			} else {
				$result = '';
			}
		}
		return $result;
	}

	public final function getCapitalsourceById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getCapitalsourceById/' . $id . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getCapitalsourceByIdResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\capitalsource' );
			$result = parent::map( $getCapitalsourceByIdResponse->getCapitalsourceTransport() );
			$result = parent::map( $result, 'Capitalsource' );
		}
		return $result;
	}

	public final function getAllCapitalsourceInitials() {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllInitials/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getAllCapitalsourceInitialsResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\capitalsource' );
			$result = $getAllCapitalsourceInitialsResponse->getInitials();
		}
		return $result;
	}

	public final function getAllCapitalsourceCount() {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/countAllCapitalsources/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$countAllCapitalsourcesResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\capitalsource' );
			$result = $countAllCapitalsourcesResponse->getCount();
		}
		return $result;
	}

	public final function createCapitalsource(array $capitalsource) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/createCapitalsource/' . $this->sessionId;
		$capitalsource = parent::map( $capitalsource, ClientArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE );
		$capitalsourceTransport = parent::map( $capitalsource, JsonArrayMapperEnum::CAPITALSOURCE_TRANSPORT );

		$request = new createCapitalsourceRequest();
		$request->setCapitalsourceTransport( $capitalsourceTransport );
		return self::postJson( $url, parent::json_encode_response( $request ) );
	}

	public final function updateCapitalsource(array $capitalsource) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/updateCapitalsource/' . $this->sessionId;
		$capitalsource = parent::map( $capitalsource, ClientArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE );
		$capitalsourceTransport = parent::map( $capitalsource, JsonArrayMapperEnum::CAPITALSOURCE_TRANSPORT );

		$request = new updateCapitalsourceRequest();
		$request->setCapitalsourceTransport( $capitalsourceTransport );
		return self::putJson( $url, parent::json_encode_response( $request ) );
	}

	public final function deleteCapitalsource($id) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/deleteCapitalsourceById/' . $id . '/' . $this->sessionId;
		return self::deleteJson( $url );
	}

	/*
	 * ContractpartnerService
	 */
	public final function getAllContractpartner() {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getAllContractpartner/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getAllContractpartnerResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\contractpartner' );
			if (is_array( $getAllContractpartnerResponse->getContractpartnerTransport() )) {
				$result = parent::mapArray( $getAllContractpartnerResponse->getContractpartnerTransport() );
				$result = parent::mapArray( $result, 'Contractpartner' );
			} else {
				$result = '';
			}
		}
		return $result;
	}

	public final function getAllContractpartnerByInitial($initial) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getAllContractpartnerByInitial/' . $initial . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getAllContractpartnerByInitialResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\contractpartner' );
			if (is_array( $getAllContractpartnerByInitialResponse->getContractpartnerTransport() )) {
				$result = parent::mapArray( $getAllContractpartnerByInitialResponse->getContractpartnerTransport() );
				$result = parent::mapArray( $result, 'Contractpartner' );
			} else {
				$result = '';
			}
		}
		return $result;
	}

	public final function getContractpartnerById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getContractpartnerById/' . $id . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getContractpartnerByIdResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\contractpartner' );
			$result = parent::map( $getContractpartnerByIdResponse->getContractpartnerTransport() );
			$result = parent::map( $result, 'Contractpartner' );
		}
		return $result;
	}

	public final function getAllContractpartnerInitials() {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getAllInitials/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getAllContractpartnerInitialsResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\contractpartner' );
			$result = $getAllContractpartnerInitialsResponse->getInitials();
		}
		return $result;
	}

	public final function getAllContractpartnerCount() {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/countAllContractpartner/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$countAllContractpartnerResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\contractpartner' );
			$result = $countAllContractpartnerResponse->getCount();
		}
		return $result;
	}

	public final function createContractpartner(array $contractpartner) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/createContractpartner/' . $this->sessionId;
		$contractpartner = parent::map( $contractpartner, ClientArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE );
		$contractpartnerTransport = parent::map( $contractpartner, JsonArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );

		$request = new createContractpartnerRequest();
		$request->setContractpartnerTransport( $contractpartnerTransport );
		return self::postJson( $url, parent::json_encode_response( $request ) );
	}

	public final function updateContractpartner(array $contractpartner) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/updateContractpartner/' . $this->sessionId;
		$contractpartner = parent::map( $contractpartner, ClientArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE );
		$contractpartnerTransport = parent::map( $contractpartner, JsonArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );

		$request = new updateContractpartnerRequest();
		$request->setContractpartnerTransport( $contractpartnerTransport );
		return self::putJson( $url, parent::json_encode_response( $request ) );
	}

	public final function deleteContractpartner($id) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/deleteContractpartnerById/' . $id . '/' . $this->sessionId;
		return self::deleteJson( $url );
	}

	/*
	 * PreDefMoneyflowService
	 */
	public final function getAllPreDefMoneyflowInitials() {
		$url = URLPREFIX . SERVERPREFIX . 'preDefMoneyflowService/getAllInitials/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getAllPreDefMoneyflowInitialsResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\predefmoneyflow' );
			$result = $getAllPreDefMoneyflowInitialsResponse->getInitials();
		}
		return $result;
	}

	public final function getAllPreDefMoneyflowCount() {
		$url = URLPREFIX . SERVERPREFIX . 'preDefMoneyflowService/countAllPreDefMoneyflows/' . $this->sessionId;
		$result = self::getJson( $url );
		if ($result) {
			$countAllPreDefMoneyflowsResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\predefmoneyflow' );
			$result = $countAllPreDefMoneyflowsResponse->getCount();
		}
		return $result;
	}

	public final function getAllPreDefMoneyflows() {
		$url = URLPREFIX . SERVERPREFIX . 'preDefMoneyflowService/getAllPreDefMoneyflows/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getAllPreDefMoneyflowsResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\predefmoneyflow' );
			if (is_array( $getAllPreDefMoneyflowsResponse->getPreDefMoneyflowTransport() )) {
				$result = parent::mapArray( $getAllPreDefMoneyflowsResponse->getPreDefMoneyflowTransport() );
				$result = parent::mapArray( $result, 'PreDefMoneyflow' );
			} else {
				$result = '';
			}
		}
		return $result;
	}

	public final function getAllPreDefMoneyflowsByInitial($initial) {
		$url = URLPREFIX . SERVERPREFIX . 'preDefMoneyflowService/getAllPreDefMoneyflowsByInitial/' . $initial . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getAllPreDefMoneyflowsByInitialResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\predefmoneyflow' );
			if (is_array( $getAllPreDefMoneyflowsByInitialResponse->getPreDefMoneyflowTransport() )) {
				$result = parent::mapArray( $getAllPreDefMoneyflowsByInitialResponse->getPreDefMoneyflowTransport() );
				$result = parent::mapArray( $result, 'PreDefMoneyflow' );
			} else {
				$result = '';
			}
		}
		return $result;
	}
}

?>