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
// $Id: CallServer.php,v 1.8 2013/08/30 16:33:26 olivleh1 Exp $
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

class CallServer extends AbstractJsonSender {
	private $sessionId;
	private static $instance;

	private function __construct() {
		parent::addMapper( 'rest\model\mapper\JsonToCapitalsourceMapper', JsonArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE );
		parent::addMapper( 'rest\model\mapper\JsonToContractpartnerMapper', JsonArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE );
		parent::addMapper( 'rest\model\mapper\JsonToMoneyflowMapper', JsonArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );
		parent::addMapper( 'rest\model\mapper\JsonToUserMapper', JsonArrayMapperEnum::USER_ARRAY_TYPE );
		parent::addMapper( 'rest\model\mapper\JsonToSessionMapper', JsonArrayMapperEnum::SESSION_ARRAY_TYPE );
		parent::addMapper( 'rest\model\mapper\validation\JsonToValidationResultMapper', JsonArrayMapperEnum::VALIDATION_RESULT_ARRAY_TYPE );
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
			return parent::map( $result['ValidationResult'], JsonArrayMapperEnum::VALIDATION_RESULT_ARRAY_TYPE );
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
			$jsonArray = reset( $result );
			$result = parent::map( $jsonArray, JsonArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );
		}
		return $result;
	}

	public final function getMoneyflowsByMonth($year, $month) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getMoneyflowsByMonth/' . $year . '/' . $month . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = array ();
			foreach ( $jsonArray as $json ) {
				$result [] = parent::map( $json, JsonArrayMapperEnum::MONEYFLOW_ARRAY_TYPE );
			}
		}
		return $result;
	}

	public final function getAllMoneyflowYears() {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getAllYears/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$result = reset( $result );
		}
		return $result;
	}

	public final function getAllMoneyflowMonth($year) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getAllMonth/' . $year . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$result = reset( $result );
		}
		return $result;
	}

	public final function createMoneyflow(Moneyflow $moneyflow) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/createMoneyflow/' . $this->sessionId;
		return self::postJson( $url, parent::json_encode( $moneyflow ) );
	}

	public final function updateMoneyflow(Moneyflow $moneyflow) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/updateMoneyflow/' . $this->sessionId;
		return self::putJson( $url, parent::json_encode( $moneyflow ) );
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
			$jsonArray = reset( $result );
			$result = array ();
			foreach ( $jsonArray as $json ) {
				$result [] = parent::map( $json, JsonArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE );
			}
		}
		return $result;
	}

	public final function getAllCapitalsourcesByDateRange($validfrom, $validtil) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllCapitalsourcesByDateRange/' . $validfrom . '/' . $validtil . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = array ();
			foreach ( $jsonArray as $json ) {
				$result [] = parent::map( $json, JsonArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE );
			}
		}
		return $result;
	}

	public final function getAllCapitalsourcesByInitial($initial) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllCapitalsourcesByInitial/' . $initial . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = array ();
			foreach ( $jsonArray as $json ) {
				$result [] = parent::map( $json, JsonArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE );
			}
		}
		return $result;
	}

	public final function getCapitalsourceById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getCapitalsourceById/' . $id . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = parent::map( $jsonArray, JsonArrayMapperEnum::CAPITALSOURCE_ARRAY_TYPE );
		}
		return $result;
	}

	public final function getAllCapitalsourceInitials() {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllInitials/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$result = reset( $result );
		}
		return $result;
	}

	public final function getAllCapitalsourceCount() {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/countAllCapitalsources/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$result = reset( $result );
		}
		return $result;
	}

	public final function createCapitalsource(Capitalsource $capitalsource) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/createCapitalsource/' . $this->sessionId;
		return self::postJson( $url, parent::json_encode( $capitalsource ) );
	}

	public final function updateCapitalsource(Capitalsource $capitalsource) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/updateCapitalsource/' . $this->sessionId;
		return self::putJson( $url, parent::json_encode( $capitalsource ) );
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
			$jsonArray = reset( $result );
			$result = array ();
			foreach ( $jsonArray as $json ) {
				$result [] = parent::map( $json, JsonArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE );
			}
		}
		return $result;
	}

	public final function getAllContractpartnerByInitial($initial) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getAllContractpartnerByInitial/' . $initial . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = array ();
			foreach ( $jsonArray as $json ) {
				$result [] = parent::map( $json, JsonArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE );
			}
		}
		return $result;
	}

	public final function getContractpartnerById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getContractpartnerById/' . $id . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = parent::map( $jsonArray, JsonArrayMapperEnum::CONTRACTPARTNER_ARRAY_TYPE );
		}
		return $result;
	}

	public final function getAllContractpartnerInitials() {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getAllInitials/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$result = reset( $result );
		}
		return $result;
	}

	public final function getAllContractpartnerCount() {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/countAllContractpartner/' . $this->sessionId;
		$result = self::getJson( $url );
		if ($result) {
			$result = reset( $result );
		}
		return $result;
	}

	public final function createContractpartner(Contractpartner $contractpartner) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/createContractpartner/' . $this->sessionId;
		return self::postJson( $url, parent::json_encode( $contractpartner ) );
	}

	public final function updateContractpartner(Contractpartner $contractpartner) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/updateContractpartner/' . $this->sessionId;
		return self::putJson( $url, parent::json_encode( $contractpartner ) );
	}

	public final function deleteContractpartner($id) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/deleteContractpartnerById/' . $id . '/' . $this->sessionId;
		return self::deleteJson( $url );
	}
}

?>