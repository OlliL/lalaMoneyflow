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
// $Id: CompareDataControllerHandler.php,v 1.3 2014/02/15 19:20:48 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\client\util\CallServerUtil;
use rest\base\AbstractJsonSender;
use rest\client\mapper\ClientArrayMapperEnum;
use rest\base\JsonAutoMapper;
use rest\client\util\DateUtil;
use rest\api\model\comparedata\compareDataRequest;

class CompareDataControllerHandler extends AbstractJsonSender {
	private static $instance;
	private static $callServer;

	protected function __construct() {
		parent::addMapper( 'rest\client\mapper\ArrayToCompareDataFormatTransportMapper', ClientArrayMapperEnum::COMPAREDATAFORMAT_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToCompareDataDatasetTransportMapper', ClientArrayMapperEnum::COMPAREDATADATASET_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToCapitalsourceTransportMapper', ClientArrayMapperEnum::CAPITALSOURCE_TRANSPORT );
		parent::addMapper( 'rest\client\mapper\ArrayToMoneyflowTransportMapper', ClientArrayMapperEnum::MONEYFLOW_TRANSPORT );
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new CompareDataControllerHandler();
			self::$callServer = CallServerUtil::getInstance();
		}
		return self::$instance;
	}

	public final function showCompareDataForm() {
		$url = URLPREFIX . SERVERPREFIX . 'comparedata/showCompareDataForm/' . self::$callServer->getSessionId();
		$response = self::$callServer->getJson( $url );
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
			$result['selected_format'] = $showCompareDataForm->getSelectedDataFormat();
			$result['selected_capitalsource'] = $showCompareDataForm->getSelectedCapitalsource();
		}

		return $result;
	}

	public final function compareData(array $compareData) {
		$url = URLPREFIX . SERVERPREFIX . 'comparedata/compareData/' . self::$callServer->getSessionId();

		$request = new compareDataRequest();
		$request->setCapitalSourceId( $compareData ['mcs_capitalsourceid'] );
		$request->setEndDate( DateUtil::convertClientDateToTransport( $compareData ['enddate'] ) );
		$request->setFileContents( base64_encode( $compareData ['filecontents'] ) );
		$request->setFormatId( $compareData ['format'] );
		$request->setStartDate( DateUtil::convertClientDateToTransport( $compareData ['startdate'] ) );

		$response = self::$callServer->putJson( $url, parent::json_encode_response( $request ) );
		if (is_array( $response )) {
			$compareDataResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\comparedata' );
			if (is_array( $compareDataResponse->getCompareDataMatchingTransport() )) {
				foreach ( $compareDataResponse->getCompareDataMatchingTransport() as $key => $compareDataMatchingTransport ) {
					$result ['matching'] [$key] ['moneyflow'] = parent::map( $compareDataMatchingTransport->getMoneyflowTransport() );
					$result ['matching'] [$key] ['file'] = parent::map( $compareDataMatchingTransport->getCompareDataDatasetTransport() );
				}
			} else {
				$result ['matching'] = array ();
			}
			if (is_array( $compareDataResponse->getCompareDataNotInDatabaseTransport() )) {
				foreach ( $compareDataResponse->getCompareDataNotInDatabaseTransport() as $key => $compareDataNotInDatabaseTransport ) {
					$result ['not_in_db'] [$key] ['file'] = parent::map( $compareDataNotInDatabaseTransport->getCompareDataDatasetTransport() );
				}
			} else {
				$result ['not_in_db'] = array ();
			}
			if (is_array( $compareDataResponse->getCompareDataNotInFileTransport() )) {
				foreach ( $compareDataResponse->getCompareDataNotInFileTransport() as $key => $compareDataNotInFileTransport ) {
					$result ['not_in_file'] [$key] ['moneyflow'] = parent::map( $compareDataNotInFileTransport->getMoneyflowTransport() );
				}
			} else {
				$result ['not_in_file'] = array ();
			}
			if (is_array( $compareDataResponse->getCompareDataWrongCapitalsourceTransport() )) {
				foreach ( $compareDataResponse->getCompareDataWrongCapitalsourceTransport() as $key => $compareDataWrongCapitalsourceTransport ) {
					$result ['wrong_source'] [$key] ['moneyflow'] = parent::map( $compareDataWrongCapitalsourceTransport->getMoneyflowTransport() );
					$result ['wrong_source'] [$key] ['file'] = parent::map( $compareDataWrongCapitalsourceTransport->getCompareDataDatasetTransport() );
				}
			} else {
				$result ['wrong_source'] = array ();
			}
			if ($compareDataResponse->getCapitalsourceTransport() instanceof CapitalsourceTransport) {
				$result ['capitalsource'] = parent::map( $compareDataResponse->getCapitalsourceTransport() );
			} else {
				$result ['capitalsource'] = array ();
			}
		}

		return $result;
	}
}

?>