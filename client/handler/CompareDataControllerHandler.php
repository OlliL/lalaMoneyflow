<?php
//
// Copyright (c) 2013-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: CompareDataControllerHandler.php,v 1.17 2016/09/17 22:46:54 olivleh1 Exp $
//
namespace client\handler;

use client\util\DateUtil;
use api\model\comparedata\compareDataRequest;
use api\model\comparedata\showCompareDataFormResponse;
use api\model\comparedata\compareDataResponse;
use client\mapper\ArrayToCompareDataFormatTransportMapper;
use client\mapper\ArrayToCompareDataDatasetTransportMapper;
use client\mapper\ArrayToCapitalsourceTransportMapper;
use client\mapper\ArrayToMoneyflowTransportMapper;
use api\model\transport\CapitalsourceTransport;
use base\Singleton;

class CompareDataControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
		parent::addMapper( ArrayToCompareDataFormatTransportMapper::getClass() );
		parent::addMapper( ArrayToCompareDataDatasetTransportMapper::getClass() );
		parent::addMapper( ArrayToCapitalsourceTransportMapper::getClass() );
		parent::addMapper( ArrayToMoneyflowTransportMapper::getClass() );
	}

	protected final function getCategory() {
		return 'comparedata';
	}

	public final function showCompareDataForm() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showCompareDataFormResponse) {
			if (is_array( $response->getCapitalsourceTransport() )) {
				$result ['capitalsources'] = parent::mapArray( $response->getCapitalsourceTransport() );
			} else {
				$result ['capitalsources'] = array ();
			}
			if (is_array( $response->getCompareDataFormatTransport() )) {
				$result ['comparedataformats'] = parent::mapArray( $response->getCompareDataFormatTransport() );
			} else {
				$result ['comparedataformats'] = array ();
			}
			$result ['selected_format'] = $response->getSelectedDataFormat();
			$result ['selected_capitalsource'] = $response->getSelectedCapitalsourceId();
		}

		return $result;
	}

	public final function compareData(array $compareData) {
		$request = new compareDataRequest();
		$request->setCapitalSourceId( $compareData ['mcs_capitalsourceid'] );
		$request->setEndDate( DateUtil::convertClientDateToTransport( $compareData ['enddate'] ) );
		if (array_key_exists( "filecontents", $compareData )) {
			$request->setFileContents( base64_encode( $compareData ['filecontents'] ) );
		}
		$request->setFormatId( $compareData ['format'] );
		$request->setStartDate( DateUtil::convertClientDateToTransport( $compareData ['startdate'] ) );
		$request->setUseImportedData( $compareData ['use_imported_data'] );

		$response = parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
		$result = null;
		if ($response instanceof compareDataResponse) {
			if (is_array( $response->getCompareDataMatchingTransport() )) {
				foreach ( $response->getCompareDataMatchingTransport() as $key => $compareDataMatchingTransport ) {
					$result ['matching'] [$key] ['moneyflow'] = parent::map( $compareDataMatchingTransport->getMoneyflowTransport() );
					$result ['matching'] [$key] ['file'] = parent::map( $compareDataMatchingTransport->getCompareDataDatasetTransport() );
				}
			} else {
				$result ['matching'] = array ();
			}
			if (is_array( $response->getCompareDataNotInDatabaseTransport() )) {
				foreach ( $response->getCompareDataNotInDatabaseTransport() as $key => $compareDataNotInDatabaseTransport ) {
					$result ['not_in_db'] [$key] ['file'] = parent::map( $compareDataNotInDatabaseTransport->getCompareDataDatasetTransport() );
				}
			} else {
				$result ['not_in_db'] = array ();
			}
			if (is_array( $response->getCompareDataNotInFileTransport() )) {
				foreach ( $response->getCompareDataNotInFileTransport() as $key => $compareDataNotInFileTransport ) {
					$result ['not_in_file'] [$key] ['moneyflow'] = parent::map( $compareDataNotInFileTransport->getMoneyflowTransport() );
				}
			} else {
				$result ['not_in_file'] = array ();
			}
			if (is_array( $response->getCompareDataWrongCapitalsourceTransport() )) {
				foreach ( $response->getCompareDataWrongCapitalsourceTransport() as $key => $compareDataWrongCapitalsourceTransport ) {
					$result ['wrong_source'] [$key] ['moneyflow'] = parent::map( $compareDataWrongCapitalsourceTransport->getMoneyflowTransport() );
					$result ['wrong_source'] [$key] ['file'] = parent::map( $compareDataWrongCapitalsourceTransport->getCompareDataDatasetTransport() );
				}
			} else {
				$result ['wrong_source'] = array ();
			}
			if ($response->getCapitalsourceTransport() instanceof CapitalsourceTransport) {
				$result ['capitalsource'] = parent::map( $response->getCapitalsourceTransport() );
			} else {
				$result ['capitalsource'] = array ();
			}
			$result ['errors'] = parent::mapArrayNullable( $response->getValidationItemTransport() );
			$result ['result'] = $response->getResult();
		}

		return $result;
	}
}

?>