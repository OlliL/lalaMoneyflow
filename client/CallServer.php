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
// $Id: CallServer.php,v 1.5 2013/08/23 17:56:08 olivleh1 Exp $
//
namespace rest\client;

use \Httpful\Request;
use \Httpful\Httpful;
use \Httpful\Mime;
use \Httpful\Handlers\JsonHandler;
use rest\model\mapper\JsonToMoneyflowMapper;
use rest\model\mapper\JsonAutoMapper;
use rest\model\mapper\JsonToUserMapper;
use rest\model\Capitalsource;
use rest\base\AbstractJsonSender;
use rest\model\mapper\JsonToCapitalsourceMapper;
use rest\model\mapper\JsonToContractpartnerMapper;
use rest\model\Contractpartner;
use rest\model\Moneyflow;

class CallServer extends AbstractJsonSender {
	private static $sessionId;

	public static function setSessionId($sessionId) {
		self::$sessionId = $sessionId;
	}

	private final function handle_result($result) {
		if (! is_array( $result )) {
			echo '<font color="red"><u>Server Error occured</u><pre>' . $result . '</pre></font><br>';
			add_error( 204 );
			return false;
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
		Httpful::register( Mime::JSON, new JsonHandler( array (
				'decode_as_array' => true
		) ) );
		$response = Request::get( $url )->withoutStrictSsl()->send();
		if ($response->code == 204) {
			return false;
		} else {
			return self::handle_result( $response->body );
		}
		// $ch = curl_init();
		// curl_setopt( $ch, CURLOPT_URL, $url );
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// $result = curl_exec( $ch );
		// $result = json_decode( $result, true );
		// curl_close( $ch );
	}

	private final function postJson($url, $json) {
		$response = Request::post( $url )->withoutStrictSsl()->sendsJson()->body( $json )->send();
		if ($response->code == 204) {
			return true;
		} else {
			return self::handle_result( $response->body );
		}
	}

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
	public static final function doLogon($user, $password) {
		$url = URLPREFIX . SERVERPREFIX . 'sessionService/logon/' . $user . '/' . $password;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$result = JsonAutoMapper::mapAToB( $result );
			self::setSessionId( $result->getId() );
		}
		return $result;
		// return reset( $session );
	}

	/*
	 * UserService
	 */
	public static final function getUserById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'userService/getUserById/' . $id . '/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = JsonToUserMapper::mapAToB( $jsonArray );
		}
		return $result;
	}

	/*
	 * MoneyflowService
	 */
	public static final function getMoneyflowById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getMoneyflowById/' . $id . '/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = JsonToMoneyflowMapper::mapAToB( $jsonArray );
		}
		return $result;
	}

	public static final function getMoneyflowsByMonth($year, $month) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getMoneyflowsByMonth/' . $year . '/' . $month . '/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = array ();
			foreach ( $jsonArray as $json ) {
				$result [] = JsonToMoneyflowMapper::mapAToB( $json );
			}
		}
		return $result;
	}

	public static final function getAllMoneyflowYears() {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getAllYears/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$result = reset( $result );
		}
		return $result;
	}

	public static final function getAllMoneyflowMonth($year) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getAllMonth/' . $year . '/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$result = reset( $result );
		}
		return $result;
	}

	public static final function createMoneyflow(Moneyflow $moneyflow) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/createMoneyflow/' . self::$sessionId;
		return self::postJson( $url, parent::json_encode( $moneyflow ) );
	}

	public static final function updateMoneyflow(Moneyflow $moneyflow) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/updateMoneyflow/' . self::$sessionId;
		return self::putJson( $url, parent::json_encode( $moneyflow ) );
	}

	public static final function deleteMoneyflow($id) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/deleteMoneyflowById/' . $id . '/' . self::$sessionId;
		return self::deleteJson( $url );
	}
	/*
	 * CapitalsourceService
	 */
	public static final function getAllCapitalsources() {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllCapitalsources/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = array ();
			foreach ( $jsonArray as $json ) {
				$result [] = JsonToCapitalsourceMapper::mapAToB( $json );
			}
		}
		return $result;
	}

	public static final function getAllCapitalsourcesByDateRange($validfrom, $validtil) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllCapitalsourcesByDateRange/' . $validfrom . '/' . $validtil . '/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = array ();
			foreach ( $jsonArray as $json ) {
				$result [] = JsonToCapitalsourceMapper::mapAToB( $json );
			}
		}
		return $result;
	}

	public static final function getAllCapitalsourcesByInitial($initial) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllCapitalsourcesByInitial/' . $initial . '/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = array ();
			foreach ( $jsonArray as $json ) {
				$result [] = JsonToCapitalsourceMapper::mapAToB( $json );
			}
		}
		return $result;
	}

	public static final function getCapitalsourceById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getCapitalsourceById/' . $id . '/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = JsonToCapitalsourceMapper::mapAToB( $jsonArray );
		}
		return $result;
	}

	public static final function getAllCapitalsourceInitials() {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllInitials/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$result = reset( $result );
		}
		return $result;
	}

	public static final function getAllCapitalsourceCount() {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/countAllCapitalsources/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$result = reset( $result );
		}
		return $result;
	}

	public static final function createCapitalsource(Capitalsource $capitalsource) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/createCapitalsource/' . self::$sessionId;
		return self::postJson( $url, parent::json_encode( $capitalsource ) );
	}

	public static final function updateCapitalsource(Capitalsource $capitalsource) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/updateCapitalsource/' . self::$sessionId;
		return self::putJson( $url, parent::json_encode( $capitalsource ) );
	}

	public static final function deleteCapitalsource($id) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/deleteCapitalsourceById/' . $id . '/' . self::$sessionId;
		return self::deleteJson( $url );
	}

	/*
	 * ContractpartnerService
	 */
	public static final function getAllContractpartner() {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getAllContractpartner/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = array ();
			foreach ( $jsonArray as $json ) {
				$result [] = JsonToContractpartnerMapper::mapAToB( $json );
			}
		}
		return $result;
	}

	public static final function getAllContractpartnerByInitial($initial) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getAllContractpartnerByInitial/' . $initial . '/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = array ();
			foreach ( $jsonArray as $json ) {
				$result [] = JsonToContractpartnerMapper::mapAToB( $json );
			}
		}
		return $result;
	}

	public static final function getContractpartnerById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getContractpartnerById/' . $id . '/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$jsonArray = reset( $result );
			$result = JsonToContractpartnerMapper::mapAToB( $jsonArray );
		}
		return $result;
	}

	public static final function getAllContractpartnerInitials() {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getAllInitials/' . self::$sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$result = reset( $result );
		}
		return $result;
	}

	public static final function getAllContractpartnerCount() {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/countAllContractpartner/' . self::$sessionId;
		$result = self::getJson( $url );
		if ($result) {
			$result = reset( $result );
		}
		return $result;
	}

	public static final function createContractpartner(Contractpartner $contractpartner) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/createContractpartner/' . self::$sessionId;
		return self::postJson( $url, parent::json_encode( $contractpartner ) );
	}

	public static final function updateContractpartner(Contractpartner $contractpartner) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/updateContractpartner/' . self::$sessionId;
		return self::putJson( $url, parent::json_encode( $contractpartner ) );
	}

	public static final function deleteContractpartner($id) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/deleteContractpartnerById/' . $id . '/' . self::$sessionId;
		return self::deleteJson( $url );
	}
}

?>