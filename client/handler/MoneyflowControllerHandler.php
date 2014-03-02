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
// $Id: MoneyflowControllerHandler.php,v 1.8 2014/03/02 23:42:21 olivleh1 Exp $
//
namespace client\handler;

use client\mapper\ClientArrayMapperEnum;
use base\JsonAutoMapper;
use api\model\moneyflow\updateMoneyflowRequest;
use api\model\moneyflow\createMoneyflowsRequest;
use api\model\moneyflow\searchMoneyflowsRequest;

class MoneyflowControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		parent::addMapper( 'client\mapper\ArrayToCapitalsourceTransportMapper', ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToContractpartnerTransportMapper', ClientArrayMapperEnum::CONTRACTPARTNER_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToMoneyflowTransportMapper', ClientArrayMapperEnum::MONEYFLOW_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToPreDefMoneyflowTransportMapper', ClientArrayMapperEnum::PREDEFMONEYFLOW_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToPostingAccountTransportMapper', ClientArrayMapperEnum::POSTINGACCOUNT_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToMoneyflowSearchParamsTransportMapper', ClientArrayMapperEnum::MONEYFLOWSEARCHPARAMS_TRANSPORT );
		parent::addMapper( 'client\mapper\ArrayToMoneyflowSearchResultTransportMapper', ClientArrayMapperEnum::MONEYFLOWSEARCHRESULT_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new MoneyflowControllerHandler();
		}
		return self::$instance;
	}

	protected final function getCategory() {
		return 'moneyflow';
	}

	public final function showAddMoneyflows() {
		$response = parent::getJson( 'showAddMoneyflows' );
		if (is_array( $response )) {
			$addMoneyflow = JsonAutoMapper::mapAToB( $response, '\\api\\model\\moneyflow' );
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
			$result ['num_free_moneyflows'] = $addMoneyflow->getSettingNumberOfFreeMoneyflows();
		}
		
		return $result;
	}

	public final function showEditMoneyflow($id) {
		$response = parent::getJson( 'showEditMoneyflow', array (
				$id 
		) );
		if (is_array( $response )) {
			$showEditMoneyflow = JsonAutoMapper::mapAToB( $response, '\\api\\model\\moneyflow' );
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
		$response = parent::getJson( 'showDeleteMoneyflow', array (
				$id 
		) );
		if (is_array( $response )) {
			$showDeleteMoneyflowResponse = JsonAutoMapper::mapAToB( $response, '\\api\\model\\moneyflow' );
			$result = parent::map( $showDeleteMoneyflowResponse->getMoneyflowTransport() );
		}
		return $result;
	}

	public final function createMoneyflows(array $moneyflows) {
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
		
		$response = parent::postJson( 'createMoneyflows', parent::json_encode_response( $request ) );
		
		if ($response === true) {
			$result = true;
		} else if (is_array( $response )) {
			$createMoneyflow = JsonAutoMapper::mapAToB( $response, '\\api\\model\\moneyflow' );
			
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
			$result ['num_free_moneyflows'] = $createMoneyflow->getSettingNumberOfFreeMoneyflows();
		}
		
		return $result;
	}

	public final function updateMoneyflow(array $moneyflow) {
		$moneyflowTransport = parent::map( $moneyflow, ClientArrayMapperEnum::MONEYFLOW_TRANSPORT );
		
		$request = new updateMoneyflowRequest();
		$request->setMoneyflowTransport( $moneyflowTransport );
		$response = parent::putJson( 'updateMoneyflow', parent::json_encode_response( $request ) );
		
		if ($response === true) {
			$result = true;
		} else if (is_array( $response )) {
			$updateMoneyflow = JsonAutoMapper::mapAToB( $response, '\\api\\model\\moneyflow' );
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
			$result ['result'] = $updateMoneyflow->getResult();
		}
		return $result;
	}

	public final function deleteMoneyflow($id) {
		return parent::deleteJson( 'deleteMoneyflowById', array (
				$id 
		) );
	}

	public final function showSearchMoneyflow() {
		$response = parent::getJson( 'showSearchMoneyflowForm' );
		if (is_array( $response )) {
			$showSearchMoneyflowResponse = JsonAutoMapper::mapAToB( $response, '\\api\\model\\moneyflow' );
			if (is_array( $showSearchMoneyflowResponse->getContractpartnerTransport() )) {
				$result = parent::mapArray( $showSearchMoneyflowResponse->getContractpartnerTransport() );
			} else {
				$result = '';
			}
		}
		return $result;
	}

	public final function searchMoneyflows(array $params) {
		$searchParamsTransport = parent::map( $params, ClientArrayMapperEnum::MONEYFLOWSEARCHPARAMS_TRANSPORT );
		
		$request = new searchMoneyflowsRequest();
		$request->setMoneyflowSearchParamsTransport( $searchParamsTransport );
		$response = parent::putJson( 'searchMoneyflows', parent::json_encode_response( $request ) );
		
		if (is_array( $response )) {
			$searchMoneyflows = JsonAutoMapper::mapAToB( $response, '\\api\\model\\moneyflow' );
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