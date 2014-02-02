<?php
use rest\base\ErrorCode;
use rest\client\handler\CompareDataControllerHandler;
//
// Copyright (c) 2007-2013 Oliver Lehmann <oliver@FreeBSD.org>
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
// $Id: moduleCompare.php,v 1.36 2014/02/02 00:28:19 olivleh1 Exp $
//
require_once 'module/module.php';
require_once 'core/coreCurrencies.php';
require_once 'core/coreSettings.php';

class moduleCompare extends module {

	function moduleCompare() {
		parent::__construct();
		$this->coreCurrencies = new coreCurrencies();
		$this->coreSettings = new coreSettings();
	}

	function display_upload_form($all_data = array()) {
		$showCompareDataForm = CompareDataControllerHandler::getInstance()->showCompareDataForm();
		$format_values = $showCompareDataForm ['comparedataformats'];
		$capitalsource_values = $showCompareDataForm ['capitalsources'];

		if (count( $all_data ) === 0) {
			$all_data ['startdate'] = convert_date_to_gui( date( "Y-m-d", mktime( 0, 0, 0, date( 'm', time() ), 1, date( 'Y', time() ) ) ) );
			$all_data ['enddate'] = convert_date_to_gui( date( "Y-m-d", mktime( 0, 0, 0, date( 'm', time() ) + 1, 0, date( 'Y', time() ) ) ) );
			$all_data ['format'] = $this->coreSettings->get_compare_format( USERID );
			$all_data ['mcs_capitalsourceid'] = $this->coreSettings->get_compare_capitalsource( USERID );
		}

		$this->template->assign( 'CAPITALSOURCE_VALUES', $capitalsource_values );
		$this->template->assign( 'FORMAT_VALUES', $format_values );
		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header();
		return $this->fetch_template( 'display_upfrm_cmp_data.tpl' );
	}

	function display_analyze_form($file, $all_data) {
		$fileName = $file ['tmp_name'];
		$startDate = $all_data ['startdate'];
		$endDate = $all_data ['enddate'];
		$formatId = $all_data ['format'];
		$capitalSourceId = $all_data ['mcs_capitalsourceid'];

		$valid_data = true;

		if (! dateIsValid( $startDate )) {
			add_error( ErrorCode::DATE_FORMAT_NOT_CORRECT, array (
					GUI_DATE_FORMAT
			) );
			$all_data ['startdate_error'] = 1;
			$valid_data = false;
		}

		if (! dateIsValid( $endDate )) {
			add_error( ErrorCode::DATE_FORMAT_NOT_CORRECT, array (
					GUI_DATE_FORMAT
			) );
			$all_data ['enddate_error'] = 1;
			$valid_data = false;
		}

		if (! $fileName) {
			add_error( ErrorCode::FILEUPLOAD_FAILED );
			$valid_data = false;
		}

		if ($valid_data === false) {
			return $this->display_upload_form( $all_data );
		}

		// update the chosen capitalsource and format in the usersettings to remember/reuse the selection next time
		if ($all_data ['mcs_capitalsourceid'] != $this->coreSettings->get_compare_capitalsource( USERID )) {
			$this->coreSettings->set_compare_capitalsource( USERID, $all_data ['mcs_capitalsourceid'] );
		}
		if ($all_data ['format'] != $this->coreSettings->get_compare_format( USERID )) {
			$this->coreSettings->set_compare_format( USERID, $all_data ['format'] );
		}

		$all_data ['filecontents'] = file_get_contents( $fileName );

		$result = CompareDataControllerHandler::getInstance()->compareData( $all_data );

		// set "owner" + remove private entries
		$result ['matching'] = $this->setOwnerAndFilterPrivate( $result ['matching'] );
		$result ['not_in_file'] = $this->setOwnerAndFilterPrivate( $result ['not_in_file'] );
		$result ['wrong_source'] = $this->setOwnerAndFilterPrivate( $result ['wrong_source'] );

		// TODO: old shit
		$displayed_currency = $this->coreCurrencies->get_displayed_currency();
		$this->template->assign( 'CURRENCY', $displayed_currency );

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
		foreach ( $compareArray as $key => $matching ) {
			if ($matching ['moneyflow'] ['mur_userid'] == USERID || $matching ['moneyflow'] ['private'] == 0) {
				if ($matching ['moneyflow'] ['mur_userid'] == USERID)
					$compareArray [$key] ['moneyflow'] ['owner'] = true;
				$newArray [] = $compareArray [$key];
			}
		}
		return $newArray;
	}
}
?>
