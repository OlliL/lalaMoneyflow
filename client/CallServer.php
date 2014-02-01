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
// $Id: CallServer.php,v 1.33 2014/02/01 10:46:44 olivleh1 Exp $
//
namespace rest\client;

use \Httpful\Request;
use \Httpful\Httpful;
use \Httpful\Mime;
use \Httpful\Handlers\JsonHandler;
use rest\base\AbstractJsonSender;
use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;
use rest\api\model\capitalsource\createCapitalsourceRequest;
use rest\api\model\capitalsource\updateCapitalsourceRequest;
use rest\api\model\contractpartner\createContractpartnerRequest;
use rest\api\model\contractpartner\updateContractpartnerRequest;
use rest\api\model\moneyflow\updateMoneyflowRequest;
use rest\api\model\moneyflow\createMoneyflowsRequest;
use rest\api\model\predefmoneyflow\createPreDefMoneyflowRequest;
use rest\api\model\predefmoneyflow\updatePreDefMoneyflowRequest;
use rest\api\model\comparedata\compareDataRequest;
use rest\base\ErrorCode;
use rest\client\util\DateUtil;

class CallServer extends AbstractJsonSender {
	private $sessionId;
	private static $instance;

	private function __construct() {
		parent::addMapper( 'rest\client\mapper\ArrayToCapitalsourceTransportMapper', ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToContractpartnerTransportMapper', ClientArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToMoneyflowTransportMapper', ClientArrayMapperEnum::MONEYFLOW_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToPreDefMoneyflowTransportMapper', ClientArrayMapperEnum::PREDEFMONEYFLOW_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToUserTransportMapper', ClientArrayMapperEnum::USER_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToValidationItemTransportMapper', ClientArrayMapperEnum::VALIDATIONITEM_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToPostingAccountTransportMapper', ClientArrayMapperEnum::POSTINGACCOUNT_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToCompareDataFormatTransportMapper', ClientArrayMapperEnum::COMPAREDATAFORMAT_TRANSPORT );
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
			add_error( ErrorCode::ATTENTION );
			return false;
		} else if (array_key_exists( 'validationResponse', $result )) {
			$validationResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\validation' );
			$validation ['is_valid'] = $validationResponse->getResult();
			$validation ['errors'] = parent::mapArray( $validationResponse->getValidationItemTransport(), ClientArrayMapperEnum::VALIDATIONITEM_TRANSPORT );
			return ($validation);
		} else if (array_key_exists( 'error', $result )) {
			if ($result ['error'] ['code'] < 0) {
				echo '<font color="red"><u>Server Error occured</u><pre>' . $result ['error'] ['message'] . '</pre></font><br>';
				add_error( ErrorCode::ATTENTION );
			}
			add_error( $result ['error'] ['code'] );
			return false;
		}
		return $result;
	}

	private final function getJson($url) {
		// response = Request::get( $url )->withoutStrictSsl()->addOnCurlOption( CURLOPT_ENCODING, 'compress, deflate, gzip' )->send();
		$response = Request::get( $url )->withoutStrictSsl()->send();
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
			$doLogonResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\session' );
			$result = array (
					'mur_userid' => $doLogonResponse->getUserid(),
					'username' => $doLogonResponse->getUserName(),
					'sessionid' => $doLogonResponse->getSessionId()
			);
		}
		return $result;
	}

	/*
	 * UserService
	 */
	public final function getUserById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'userService/getUserById/' . $id . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$getUserByIdResponse = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\user' );
			$result = parent::map( $getUserByIdResponse->getUserTransport() );
		}
		return $result;
	}

	/*
	 * Report
	 */
	public final function listReports($year, $month) {
		$url = URLPREFIX . SERVERPREFIX . 'report/listReports/' . $year . '/' . $month . '/' . $this->sessionId;
		$result = self::getJson( $url );
		if (is_array( $result )) {
			$listReports = JsonAutoMapper::mapAToB( $result, '\\rest\\api\\model\\report' );
			if (is_array( $listReports->getMoneyflowTransport() )) {
				$result ['moneyflows'] = parent::mapArray( $listReports->getMoneyflowTransport() );
			} else {
				$result ['moneyflows'] = '';
			}
			if (is_array( $listReports->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $listReports->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = '';
			}
			$result ['allYears'] = $listReports->getAllYears();
			$result ['allMonth'] = $listReports->getAllMonth();
			$result ['year'] = $listReports->getYear();
			$result ['month'] = $listReports->getMonth();
		}

		return $result;
	}

	/*
	 * MoneyflowService
	 */
	public final function showAddMoneyflows() {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflow/showAddMoneyflows/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$addMoneyflow = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\moneyflow' );
			if (is_array( $addMoneyflow->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $addMoneyflow->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
			if (is_array( $addMoneyflow->getContractpartnerTransport() )) {
				$result ['contractpartner'] = parent::mapArray( $addMoneyflow->getContractpartnerTransport() );
			} else {
				$result ['contractpartner'] = array ();
			}
			if (is_array( $addMoneyflow->getPreDefMoneyflowTransport() )) {
				$result ['predefmoneyflows'] = parent::mapArray( $addMoneyflow->getPreDefMoneyflowTransport() );
			} else {
				$result ['predefmoneyflows'] = array ();
			}
			if (is_array( $addMoneyflow->getPostingAccountTransport() )) {
				$result ['postingaccounts'] = parent::mapArray( $addMoneyflow->getPostingAccountTransport() );
			} else {
				$result ['postingaccounts'] = array ();
			}
		}

		return $result;
	}

	public final function showEditMoneyflow($id) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflow/showEditMoneyflow/' . $id . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$showEditMoneyflow = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\moneyflow' );
			if (is_array( $showEditMoneyflow->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $showEditMoneyflow->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
			if (is_array( $showEditMoneyflow->getContractpartnerTransport() )) {
				$result ['contractpartner'] = parent::mapArray( $showEditMoneyflow->getContractpartnerTransport() );
			} else {
				$result ['contractpartner'] = array ();
			}
			if ($showEditMoneyflow->getMoneyflowTransport()) {
				$result ['moneyflow'] = parent::map( $showEditMoneyflow->getMoneyflowTransport() );
			} else {
				$result ['moneyflow'] = array ();
			}
			if (is_array( $showEditMoneyflow->getPostingAccountTransport() )) {
				$result ['postingaccounts'] = parent::mapArray( $showEditMoneyflow->getPostingAccountTransport() );
			} else {
				$result ['postingaccounts'] = array ();
			}
		}

		return $result;
	}

	public final function showDeleteMoneyflow($id) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflow/showDeleteMoneyflow/' . $id . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$getMoneyflowByIdResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\moneyflow' );
			$result = parent::map( $getMoneyflowByIdResponse->getMoneyflowTransport() );
		}
		return $result;
	}

	/**
	 *
	 * @deprecated to be replaced by a new specific REST-Call
	 */
	public final function getMoneyflowById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getMoneyflowById/' . $id . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$getMoneyflowByIdResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\moneyflow' );
			$result = parent::map( $getMoneyflowByIdResponse->getMoneyflowTransport() );
		}
		return $result;
	}

	/**
	 *
	 * @deprecated to be replaced by a new specific REST-Call
	 */
	public final function getAllMoneyflowsByDateRangeCapitalsourceId($validfrom, $validtil, $capitalsourceId) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/getAllMoneyflowsByDateRangeCapitalsourceId/' . $validfrom . '/' . $validtil . '/' . $capitalsourceId . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$getAllMoneyflowsByDateRangeCapitalsourceIdResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\moneyflow' );
			if (is_array( $getAllMoneyflowsByDateRangeCapitalsourceIdResponse->getMoneyflowTransport() )) {
				$result = parent::mapArray( $getAllMoneyflowsByDateRangeCapitalsourceIdResponse->getMoneyflowTransport() );
			} else {
				$result = '';
			}
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
		$moneyflowTransport = parent::mapArray( $moneyflows, ClientArrayMapperEnum::MONEYFLOW_TRANSPORT );

		$request = new createMoneyflowsRequest();
		$request->setMoneyflowTransport( $moneyflowTransport );
		$request->setUsedPreDefMoneyflowIds( $preDefMoneyflowIds );

		$response = self::postJson( $url, parent::json_encode_response( $request ) );

		if ($response === true) {
			$result = true;
		} else if (is_array( $response )) {
			$createMoneyflow = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\moneyflow' );

			if (is_array( $createMoneyflow->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $createMoneyflow->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
			if (is_array( $createMoneyflow->getContractpartnerTransport() )) {
				$result ['contractpartner'] = parent::mapArray( $createMoneyflow->getContractpartnerTransport() );
			} else {
				$result ['contractpartner'] = array ();
			}
			if (is_array( $createMoneyflow->getPreDefMoneyflowTransport() )) {
				$result ['predefmoneyflows'] = parent::mapArray( $createMoneyflow->getPreDefMoneyflowTransport() );
			} else {
				$result ['predefmoneyflows'] = array ();
			}
			if (is_array( $createMoneyflow->getPostingAccountTransport() )) {
				$result ['postingaccounts'] = parent::mapArray( $createMoneyflow->getPostingAccountTransport() );
			} else {
				$result ['postingaccounts'] = array ();
			}
			if (is_array( $createMoneyflow->getValidationItemTransport() )) {
				$result ['errors'] = $response ['createMoneyflowsResponse'] ['validationItemTransport'];
			} else {
				$result ['errors'] = array ();
			}
			$result ['result'] = $createMoneyflow->getResult();
		}

		return $result;
	}

	public final function updateMoneyflow(array $moneyflow) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/updateMoneyflow/' . $this->sessionId;
		$moneyflowTransport = parent::map( $moneyflow, ClientArrayMapperEnum::MONEYFLOW_TRANSPORT );

		$request = new updateMoneyflowRequest();
		$request->setMoneyflowTransport( $moneyflowTransport );
		$response = self::putJson( $url, parent::json_encode_response( $request ) );

		if ($response === true) {
			$result = true;
		} else if (is_array( $response )) {
			$updateMoneyflow = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\moneyflow' );
			if (is_array( $updateMoneyflow->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $updateMoneyflow->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
			if (is_array( $updateMoneyflow->getContractpartnerTransport() )) {
				$result ['contractpartner'] = parent::mapArray( $updateMoneyflow->getContractpartnerTransport() );
			} else {
				$result ['contractpartner'] = array ();
			}
			if (is_array( $updateMoneyflow->getPostingAccountTransport() )) {
				$result ['postingaccounts'] = parent::mapArray( $updateMoneyflow->getPostingAccountTransport() );
			} else {
				$result ['postingaccounts'] = array ();
			}
			if (is_array( $updateMoneyflow->getValidationItemTransport() )) {
				$result ['errors'] = $response ['updateMoneyflowResponse'] ['validationItemTransport'];
			} else {
				$result ['errors'] = array ();
			}
			$result ['result'] == $updateMoneyflow->getResult();
		}
		return $result;
	}

	public final function deleteMoneyflow($id) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflowService/deleteMoneyflowById/' . $id . '/' . $this->sessionId;
		return self::deleteJson( $url );
	}
	/*
	 * CapitalsourceService
	 */
	public final function showCapitalsourceList($maxRows, $restriction) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsource/showCapitalsourceList/' . $maxRows . '/' . $restriction . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$listCapitalsources = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\capitalsource' );
			if (is_array( $listCapitalsources->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $listCapitalsources->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
			$result ['initials'] = $listCapitalsources->getInitials();
		}

		return $result;
	}

	/**
	 *
	 * @deprecated to be replaced by a new specific REST-Call
	 */
	public final function getAllCapitalsources() {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllCapitalsources/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$getAllCapitalsourcesResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\capitalsource' );
			if (is_array( $getAllCapitalsourcesResponse->getCapitalsourceTransport() )) {
				$result = parent::mapArray( $getAllCapitalsourcesResponse->getCapitalsourceTransport() );
			} else {
				$result = '';
			}
		}

		return $result;
	}

	/**
	 *
	 * @deprecated to be replaced by a new specific REST-Call
	 */
	public final function getAllCapitalsourcesByDateRange($validfrom, $validtil) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getAllCapitalsourcesByDateRange/' . $validfrom . '/' . $validtil . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$getAllCapitalsourcesByDateRangeResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\capitalsource' );
			if (is_array( $getAllCapitalsourcesByDateRangeResponse->getCapitalsourceTransport() )) {
				$result = parent::mapArray( $getAllCapitalsourcesByDateRangeResponse->getCapitalsourceTransport() );
			} else {
				$result = '';
			}
		}
		return $result;
	}

	/**
	 *
	 * @deprecated to be replaced by a new specific REST-Call
	 */
	public final function getCapitalsourceById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/getCapitalsourceById/' . $id . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$getCapitalsourceByIdResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\capitalsource' );
			$result = parent::map( $getCapitalsourceByIdResponse->getCapitalsourceTransport() );
		}
		return $result;
	}

	public final function createCapitalsource(array $capitalsource) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/createCapitalsource/' . $this->sessionId;
		$capitalsourceTransport = parent::map( $capitalsource, ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );

		$request = new createCapitalsourceRequest();
		$request->setCapitalsourceTransport( $capitalsourceTransport );
		return self::postJson( $url, parent::json_encode_response( $request ) );
	}

	public final function updateCapitalsource(array $capitalsource) {
		$url = URLPREFIX . SERVERPREFIX . 'capitalsourceService/updateCapitalsource/' . $this->sessionId;
		$capitalsourceTransport = parent::map( $capitalsource, ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );

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
	public final function showContractpartnerList($maxRows, $restriction) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartner/showContractpartnerList/' . $maxRows . '/' . $restriction . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$listContractpartner = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\contractpartner' );
			if (is_array( $listContractpartner->getContractpartnerTransport() )) {
				$result ['contractpartner'] = parent::mapArray( $listContractpartner->getContractpartnerTransport() );
			} else {
				$result ['contractpartner'] = array ();
			}
			$result ['initials'] = $listContractpartner->getInitials();
		}

		return $result;
	}

	public final function showEditContractpartner($id) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartner/showEditContractpartner/' . $id . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$showEditContractpartner = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\contractpartner' );
			$result = parent::map( $showEditContractpartner->getContractpartnerTransport() );
		}
		return $result;
	}

	public final function showDeleteContractpartner($id) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartner/showDeleteContractpartner/' . $id . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$showDeleteContractpartner = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\contractpartner' );
			$result = parent::map( $showDeleteContractpartner->getContractpartnerTransport() );
		}
		return $result;
	}

	/**
	 *
	 * @deprecated to be replaced by a new specific REST-Call
	 */
	public final function getAllContractpartner() {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getAllContractpartner/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$getAllContractpartnerResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\contractpartner' );
			if (is_array( $getAllContractpartnerResponse->getContractpartnerTransport() )) {
				$result = parent::mapArray( $getAllContractpartnerResponse->getContractpartnerTransport() );
			} else {
				$result = '';
			}
		}
		return $result;
	}

	/**
	 *
	 * @deprecated to be replaced by a new specific REST-Call
	 */
	public final function getContractpartnerById($id) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/getContractpartnerById/' . $id . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$getContractpartnerByIdResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\contractpartner' );
			$result = parent::map( $getContractpartnerByIdResponse->getContractpartnerTransport() );
		}
		return $result;
	}

	public final function createContractpartner(array $contractpartner) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/createContractpartner/' . $this->sessionId;
		$contractpartnerTransport = parent::map( $contractpartner, ClientArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );

		$request = new createContractpartnerRequest();
		$request->setContractpartnerTransport( $contractpartnerTransport );
		return self::postJson( $url, parent::json_encode_response( $request ) );
	}

	public final function updateContractpartner(array $contractpartner) {
		$url = URLPREFIX . SERVERPREFIX . 'contractpartnerService/updateContractpartner/' . $this->sessionId;
		$contractpartnerTransport = parent::map( $contractpartner, ClientArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );

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
	public final function showPreDefMoneyflowList($maxRows, $restriction) {
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/showPreDefMoneyflowList/' . $maxRows . '/' . $restriction . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$listPreDefMoneyflows = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\predefmoneyflow' );
			if (is_array( $listPreDefMoneyflows->getPreDefMoneyflowTransport() )) {
				$result ['predefmoneyflows'] = parent::mapArray( $listPreDefMoneyflows->getPreDefMoneyflowTransport() );
			} else {
				$result ['predefmoneyflows'] = array ();
			}
			$result ['initials'] = $listPreDefMoneyflows->getInitials();
		}

		return $result;
	}

	public final function showEditPreDefMoneyflow($id) {
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/showEditPreDefMoneyflow/' . $id . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$showEditPreDefMoneyflow = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\predefmoneyflow' );
			if (is_array( $showEditPreDefMoneyflow->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $showEditPreDefMoneyflow->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
			if (is_array( $showEditPreDefMoneyflow->getContractpartnerTransport() )) {
				$result ['contractpartner'] = parent::mapArray( $showEditPreDefMoneyflow->getContractpartnerTransport() );
			} else {
				$result ['contractpartner'] = array ();
			}
			if ($showEditPreDefMoneyflow->getPreDefMoneyflowTransport()) {
				$result ['predefmoneyflow'] = parent::map( $showEditPreDefMoneyflow->getPreDefMoneyflowTransport() );
			} else {
				$result ['predefmoneyflow'] = array ();
			}
			if (is_array( $showEditPreDefMoneyflow->getPostingAccountTransport() )) {
				$result ['postingaccounts'] = parent::mapArray( $showEditPreDefMoneyflow->getPostingAccountTransport() );
			} else {
				$result ['postingaccounts'] = array ();
			}
		}

		return $result;
	}

	public final function showCreatePreDefMoneyflow() {
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/showCreatePreDefMoneyflow/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$showCreatePreDefMoneyflow = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\predefmoneyflow' );
			if (is_array( $showCreatePreDefMoneyflow->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $showCreatePreDefMoneyflow->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
			if (is_array( $showCreatePreDefMoneyflow->getContractpartnerTransport() )) {
				$result ['contractpartner'] = parent::mapArray( $showCreatePreDefMoneyflow->getContractpartnerTransport() );
			} else {
				$result ['contractpartner'] = array ();
			}
			if (is_array( $showCreatePreDefMoneyflow->getPostingAccountTransport() )) {
				$result ['postingaccounts'] = parent::mapArray( $showCreatePreDefMoneyflow->getPostingAccountTransport() );
			} else {
				$result ['postingaccounts'] = array ();
			}
		}

		return $result;
	}

	public final function showDeletePreDefMoneyflow($id) {
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/showDeletePreDefMoneyflow/' . $id . '/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$showDeletePreDefMoneyflow = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\predefmoneyflow' );
			$result = parent::map( $showDeletePreDefMoneyflow->getPreDefMoneyflowTransport() );
		}
		return $result;
	}

	public final function createPreDefMoneyflow(array $preDefMoneyflow) {
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/createPreDefMoneyflow/' . $this->sessionId;
		$preDefMoneyflowTransport = parent::map( $preDefMoneyflow, ClientArrayMapperEnum::PREDEFMONEYFLOW_TRANSPORT );

		$request = new createPreDefMoneyflowRequest();
		$request->setPreDefMoneyflowTransport( $preDefMoneyflowTransport );
		$response = self::postJson( $url, parent::json_encode_response( $request ) );

		if ($response === true) {
			$result = true;
		} else if (is_array( $response )) {
			$reatePreDefMoneyflow = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\predefmoneyflow' );
			if (is_array( $reatePreDefMoneyflow->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $reatePreDefMoneyflow->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
			if (is_array( $reatePreDefMoneyflow->getContractpartnerTransport() )) {
				$result ['contractpartner'] = parent::mapArray( $reatePreDefMoneyflow->getContractpartnerTransport() );
			} else {
				$result ['contractpartner'] = array ();
			}
			if (is_array( $reatePreDefMoneyflow->getPostingAccountTransport() )) {
				$result ['postingaccounts'] = parent::mapArray( $reatePreDefMoneyflow->getPostingAccountTransport() );
			} else {
				$result ['postingaccounts'] = array ();
			}
			if (is_array( $reatePreDefMoneyflow->getValidationItemTransport() )) {
				$result ['errors'] = $response ['createPreDefMoneyflowResponse'] ['validationItemTransport'];
			} else {
				$result ['errors'] = array ();
			}
			$result ['result'] == $reatePreDefMoneyflow->getResult();
		}
		return $result;
	}

	public final function updatePreDefMoneyflow(array $preDefMoneyflow) {
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/updatePreDefMoneyflow/' . $this->sessionId;
		$preDefMoneyflowTransport = parent::map( $preDefMoneyflow, ClientArrayMapperEnum::PREDEFMONEYFLOW_TRANSPORT );

		$request = new updatePreDefMoneyflowRequest();
		$request->setPreDefMoneyflowTransport( $preDefMoneyflowTransport );
		$response = self::putJson( $url, parent::json_encode_response( $request ) );

		if ($response === true) {
			$result = true;
		} else if (is_array( $response )) {
			$updatePreDefMoneyflow = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\predefmoneyflow' );
			if (is_array( $updatePreDefMoneyflow->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $updatePreDefMoneyflow->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
			if (is_array( $updatePreDefMoneyflow->getContractpartnerTransport() )) {
				$result ['contractpartner'] = parent::mapArray( $updatePreDefMoneyflow->getContractpartnerTransport() );
			} else {
				$result ['contractpartner'] = array ();
			}
			if (is_array( $updatePreDefMoneyflow->getPostingAccountTransport() )) {
				$result ['postingaccounts'] = parent::mapArray( $updatePreDefMoneyflow->getPostingAccountTransport() );
			} else {
				$result ['postingaccounts'] = array ();
			}
			if (is_array( $updatePreDefMoneyflow->getValidationItemTransport() )) {
				$result ['errors'] = $response ['updatePreDefMoneyflowResponse'] ['validationItemTransport'];
			} else {
				$result ['errors'] = array ();
			}
			$result ['result'] == $updatePreDefMoneyflow->getResult();
		}
		return $result;
	}

	public final function deletePreDefMoneyflow($id) {
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/deletePreDefMoneyflow/' . $id . '/' . $this->sessionId;
		return self::deleteJson( $url );
	}

	/*
	 * PostingAccountService
	 */
	/**
	 *
	 * @deprecated to be replaced by a new specific REST-Call
	 */
	public final function getAllPostingAccounts() {
		$url = URLPREFIX . SERVERPREFIX . 'postingAccountService/getAllPostingAccounts/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$getAllPostingAccountResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\postingaccount' );
			if (is_array( $getAllPostingAccountResponse->getPostingAccountTransport() )) {
				$result = parent::mapArray( $getAllPostingAccountResponse->getPostingAccountTransport() );
			} else {
				$result = '';
			}
		}
		return $result;
	}

	/*
	 * CompareDataController
	 */
	public final function showCompareDataForm() {
		$url = URLPREFIX . SERVERPREFIX . 'comparedata/showCompareDataForm/' . $this->sessionId;
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$showCompareDataForm = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\comparedata' );

			if (is_array( $showCompareDataForm->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $showCompareDataForm->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
			if (is_array( $showCompareDataForm->getCompareDataFormatTransport() )) {
				$result ['comparedataformats'] = parent::mapArray( $showCompareDataForm->getCompareDataFormatTransport() );
			} else {
				$result ['comparedataformats'] = array ();
			}
		}

		return $result;
	}

	public final function compareData(array $compareData) {
		$url = URLPREFIX . SERVERPREFIX . 'comparedata/compareData/' . $this->sessionId;

		$request = new compareDataRequest();
		$request->setCapitalSourceId($compareData['mcs_capitalsourceid']);
		$request->setEndDate(DateUtil::convertClientDateToTransport($compareData['enddate']));
		$request->setFileContents(base64_encode($compareData['filecontents']));
		$request->setFormatId($compareData['format']);
		$request->setStartDate(DateUtil::convertClientDateToTransport($compareData['startdate']));

		$response = self::putJson( $url, parent::json_encode_response( $request ) );
		$compareDataResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\comparedata' );

		return $compareDataResponse;
	}
}

?>