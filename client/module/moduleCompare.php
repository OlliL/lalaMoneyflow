<?php
//
// Copyright (c) 2007-2015 Oliver Lehmann <oliver@laladev.org>
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
// $Id: moduleCompare.php,v 1.51 2015/11/01 12:14:09 olivleh1 Exp $
//
namespace client\module;

use base\ErrorCode;
use client\handler\CompareDataControllerHandler;
use client\util\Environment;
use client\util\ErrorHandler;

class moduleCompare extends module {

	public final function __construct() {
		parent::__construct();
	}

	public final function display_upload_form($all_data = array()) {
		$showCompareDataForm = CompareDataControllerHandler::getInstance()->showCompareDataForm();
		$format_values = $showCompareDataForm ['comparedataformats'];
		$capitalsource_values = $showCompareDataForm ['capitalsources'];
		$selected_format = $showCompareDataForm ['selected_format'];
		$selected_capitalsource = $showCompareDataForm ['selected_capitalsource'];

		if (count( $all_data ) === 0) {
			$all_data ['startdate'] = $this->convertDateToGui( date( "Y-m-d", mktime( 0, 0, 0, date( 'm', time() ), 1, date( 'Y', time() ) ) ) );
			$all_data ['enddate'] = $this->convertDateToGui( date( "Y-m-d", mktime( 0, 0, 0, date( 'm', time() ) + 1, 0, date( 'Y', time() ) ) ) );
			$all_data ['format'] = $selected_format;
			$all_data ['mcs_capitalsourceid'] = $selected_capitalsource;
		}

		$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		$this->template->assign( 'FORMAT_VALUES', $format_values );
		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header();
		return $this->fetch_template( 'display_upfrm_cmp_data.tpl' );
	}

	public final function display_analyze_form($file, $all_data) {
		$fileName = $file ['tmp_name'];
		$startDate = $all_data ['startdate'];
		$endDate = $all_data ['enddate'];
		$formatId = $all_data ['format'];

		$valid_data = true;

		if(! array_key_exists('mcs_capitalsourceid',$all_data) ) {
			$this->add_error( ErrorCode::CAPITALSOURCE_IS_NOT_SET );
			$valid_data = false;
		} else {
			$capitalSourceId = $all_data ['mcs_capitalsourceid'];
		}

		if (! $this->dateIsValid( $startDate )) {
			$this->add_error( ErrorCode::DATE_FORMAT_NOT_CORRECT, array (
					Environment::getInstance()->getSettingDateFormat()
			) );
			$all_data ['startdate_error'] = 1;
			$valid_data = false;
		}

		if (! $this->dateIsValid( $endDate )) {
			$this->add_error( ErrorCode::DATE_FORMAT_NOT_CORRECT, array (
					Environment::getInstance()->getSettingDateFormat()
			) );
			$all_data ['enddate_error'] = 1;
			$valid_data = false;
		}

		if (! $fileName) {
			$this->add_error( ErrorCode::FILEUPLOAD_FAILED );
			$valid_data = false;
		}

		if ($valid_data === true) {
			$filecontents = file_get_contents( $fileName );
			if(!mb_detect_encoding($filecontents,'UTF-8',true)) {
				$filecontents = utf8_encode($filecontents);
			}
			$all_data ['filecontents'] = $filecontents;

			$result = CompareDataControllerHandler::getInstance()->compareData( $all_data );
			if (is_array( $result ['errors'] )) {
				foreach ( $result ['errors'] as $validationResult ) {
					$valid_data = false;
					$error = $validationResult ['error'];
					$this->add_error( $error );
				}
			}
			if(ErrorHandler::getErrors()) {
				$valid_data = false;
			}
		}

		if ($valid_data === false) {
			return $this->display_upload_form( $all_data );
		}

		// set "owner" + remove private entries
		$result ['matching'] = $this->setOwnerAndFilterPrivate( $result ['matching'] );
		$result ['not_in_file'] = $this->setOwnerAndFilterPrivate( $result ['not_in_file'] );
		$result ['wrong_source'] = $this->setOwnerAndFilterPrivate( $result ['wrong_source'] );

		$this->template->assign( 'MATCHING', $result ['matching'] );
		$this->template->assign( 'NOT_IN_DB', $result ['not_in_db'] );
		$this->template->assign( 'NOT_IN_FILE', $result ['not_in_file'] );
		$this->template->assign( 'WRONG_SOURCE', $result ['wrong_source'] );
		$this->template->assign( 'CAPITALSOURCECOMMENT', $result ['capitalsource'] ['comment'] );

		$this->parse_header();
		return $this->fetch_template( 'display_analyze_cmp_data.tpl' );
	}

	/**
	 * This function removes all elements where the moneyflow is set to private and does not belong to the user.
	 * Moneyflows which belong to the user also receive an additional flag indicating this.
	 *
	 * @param unknown $compareArray
	 * @return array
	 */
	private final function setOwnerAndFilterPrivate($compareArray) {
		$newArray = array();
		foreach ( $compareArray as $key => $matching ) {
			if ($matching ['moneyflow'] ['mur_userid'] == Environment::getInstance()->getUserId() || $matching ['moneyflow'] ['private'] == 0) {
				if ($matching ['moneyflow'] ['mur_userid'] == Environment::getInstance()->getUserId())
					$compareArray [$key] ['moneyflow'] ['owner'] = true;
				$newArray [] = $compareArray [$key];
			}
		}
		return $newArray;
	}
}
?>
