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
// $Id: PreDefMoneyflowControllerHandler.php,v 1.1 2014/02/01 23:26:24 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\client\util\CallServerUtil;
use rest\base\AbstractJsonSender;
use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;
use rest\api\model\predefmoneyflow\updatePreDefMoneyflowRequest;
use rest\api\model\predefmoneyflow\createPreDefMoneyflowRequest;

class PreDefMoneyflowControllerHandler extends AbstractJsonSender {
	private static $instance;
	private static $callServer;

	protected function __construct() {
		parent::addMapper( 'rest\client\mapper\ArrayToCapitalsourceTransportMapper', ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToContractpartnerTransportMapper', ClientArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToPostingAccountTransportMapper', ClientArrayMapperEnum::POSTINGACCOUNT_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToPreDefMoneyflowTransportMapper', ClientArrayMapperEnum::PREDEFMONEYFLOW_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new PreDefMoneyflowControllerHandler();
			self::$callServer = CallServerUtil::getInstance();
		}
		return self::$instance;
	}

	public final function showPreDefMoneyflowList($maxRows, $restriction) {
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/showPreDefMoneyflowList/' . $maxRows . '/' . $restriction . '/' . self::$callServer->getSessionId();
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
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/showEditPreDefMoneyflow/' . $id . '/' . self::$callServer->getSessionId();
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
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/showCreatePreDefMoneyflow/' . self::$callServer->getSessionId();
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
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/showDeletePreDefMoneyflow/' . $id . '/' . self::$callServer->getSessionId();
		$response = self::getJson( $url );
		if (is_array( $response )) {
			$showDeletePreDefMoneyflow = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\predefmoneyflow' );
			$result = parent::map( $showDeletePreDefMoneyflow->getPreDefMoneyflowTransport() );
		}
		return $result;
	}

	public final function createPreDefMoneyflow(array $preDefMoneyflow) {
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/createPreDefMoneyflow/' . self::$callServer->getSessionId();
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
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/updatePreDefMoneyflow/' . self::$callServer->getSessionId();
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
		$url = URLPREFIX . SERVERPREFIX . 'predefmoneyflow/deletePreDefMoneyflow/' . $id . '/' . self::$callServer->getSessionId();
		return self::deleteJson( $url );
	}
}

?>