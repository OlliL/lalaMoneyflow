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
// $Id: MoneyflowControllerHandler.php,v 1.2 2014/02/14 22:02:51 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\client\util\CallServerUtil;
use rest\base\AbstractJsonSender;
use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;
use rest\api\model\moneyflow\updateMoneyflowRequest;
use rest\api\model\moneyflow\createMoneyflowsRequest;
use rest\api\model\moneyflow\searchMoneyflowsRequest;

class MoneyflowControllerHandler extends AbstractJsonSender {
	private static $instance;
	private static $callServer;

	protected function __construct() {
		parent::addMapper( 'rest\client\mapper\ArrayToCapitalsourceTransportMapper', ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToContractpartnerTransportMapper', ClientArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToMoneyflowTransportMapper', ClientArrayMapperEnum::MONEYFLOW_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToPreDefMoneyflowTransportMapper', ClientArrayMapperEnum::PREDEFMONEYFLOW_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToPostingAccountTransportMapper', ClientArrayMapperEnum::POSTINGACCOUNT_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToMoneyflowSearchParamsTransportMapper', ClientArrayMapperEnum::MONEYFLOWSEARCHPARAMS_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToMoneyflowSearchResultTransportMapper', ClientArrayMapperEnum::MONEYFLOWSEARCHRESULT_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new MoneyflowControllerHandler();
			self::$callServer = CallServerUtil::getInstance();
		}
		return self::$instance;
	}

	public final function showAddMoneyflows() {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflow/showAddMoneyflows/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
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
		$url = URLPREFIX . SERVERPREFIX . 'moneyflow/showEditMoneyflow/' . $id . '/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
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
		$url = URLPREFIX . SERVERPREFIX . 'moneyflow/showDeleteMoneyflow/' . $id . '/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$showDeleteMoneyflowResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\moneyflow' );
			$result = parent::map( $showDeleteMoneyflowResponse->getMoneyflowTransport() );
		}
		return $result;
	}

	public final function createMoneyflows(array $moneyflows) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflow/createMoneyflows/' . self::$callServer->getSessionId();

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

		$response = self::$callServer->postJson( $url, parent::json_encode_response( $request ) );

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
		$url = URLPREFIX . SERVERPREFIX . 'moneyflow/updateMoneyflow/' . self::$callServer->getSessionId();
		$moneyflowTransport = parent::map( $moneyflow, ClientArrayMapperEnum::MONEYFLOW_TRANSPORT );

		$request = new updateMoneyflowRequest();
		$request->setMoneyflowTransport( $moneyflowTransport );
		$response = self::$callServer->putJson( $url, parent::json_encode_response( $request ) );

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
		$url = URLPREFIX . SERVERPREFIX . 'moneyflow/deleteMoneyflowById/' . $id . '/' . self::$callServer->getSessionId();
		return self::$callServer->deleteJson( $url );
	}

	public final function showSearchMoneyflow() {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflow/showSearchMoneyflowForm/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
		if (is_array( $response )) {
			$showSearchMoneyflowResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\moneyflow' );
			if (is_array( $showSearchMoneyflowResponse->getContractpartnerTransport() )) {
				$result = parent::mapArray( $showSearchMoneyflowResponse->getContractpartnerTransport() );
			} else {
				$result = '';
			}
		}
		return $result;
	}

	public final function searchMoneyflows(array $params) {
		$url = URLPREFIX . SERVERPREFIX . 'moneyflow/searchMoneyflows/' . self::$callServer->getSessionId();

		$searchParamsTransport = parent::map( $params, ClientArrayMapperEnum::MONEYFLOWSEARCHPARAMS_TRANSPORT );

		$request = new searchMoneyflowsRequest();
		$request->setMoneyflowSearchParamsTransport( $searchParamsTransport );
		$response = self::$callServer->putJson( $url, parent::json_encode_response( $request ) );

		if (is_array( $response )) {
			$searchMoneyflows = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\moneyflow' );
			if (is_array( $searchMoneyflows->getMoneyflowSearchResultTransport() )) {
				$result ['search_results'] = parent::mapArray( $searchMoneyflows->getMoneyflowSearchResultTransport() );
			} else {
				$result ['search_results'] = '';
			}
			if (is_array( $searchMoneyflows->getContractpartnerTransport() )) {
				$result ['contractpartner'] = parent::mapArray( $searchMoneyflows->getContractpartnerTransport() );
			} else {
				$result ['contractpartner'] = '';
			}
			if (is_array( $searchMoneyflows->getValidationItemTransport() )) {
				$result ['errors'] = $response ['searchMoneyflowsResponse'] ['validationItemTransport'];
			} else {
				$result ['errors'] = array ();
			}
			$result ['result'] = $searchMoneyflows->getResult();
		}
		return $result;
	}
}

?>