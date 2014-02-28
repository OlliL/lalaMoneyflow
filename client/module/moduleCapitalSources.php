<?php
//
// Copyright (c) 2005-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: moduleCapitalSources.php,v 1.47 2014/02/28 17:04:59 olivleh1 Exp $
//

use rest\base\ErrorCode;
use rest\client\handler\CapitalsourceControllerHandler;
require_once 'module/module.php';
require_once 'core/coreText.php';

class moduleCapitalSources extends module {

	public final function __construct() {
		parent::__construct();
		$this->coreText = new coreText(parent::getGuiLanguage());
	}

	public final function display_list_capitalsources($letter) {
		$listCapitalsources = CapitalsourceControllerHandler::getInstance()->showCapitalsourceList( $letter );

		$all_index_letters = $listCapitalsources ['initials'];
		$all_data = $listCapitalsources ['capitalsources'];

		foreach ( $all_data as $key => $data ) {
			$all_data [$key] ['statecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_STATE', $data ['state'] );
			$all_data [$key] ['typecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_TYPE', $data ['type'] );
			if ($data ['mur_userid'] == USERID) {
				$all_data [$key] ['owner'] = true;
			} else {
				$all_data [$key] ['owner'] = false;
			}
		}
		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'COUNT_ALL_DATA', count( $all_data ) );
		$this->template->assign( 'ALL_INDEX_LETTERS', $all_index_letters );

		$this->parse_header();
		return $this->fetch_template( 'display_list_capitalsources.tpl' );
	}

	public final function display_edit_capitalsource($realaction, $capitalsourceid, $all_data) {
		if (! isset( $capitalsourceid ))
			return;

		switch ($realaction) {
			case 'save' :
				$valid_data = true;
				$all_data ['capitalsourceid'] = $capitalsourceid;
				if (! dateIsValid( $all_data ['validfrom'] )) {
					add_error( ErrorCode::DATE_FORMAT_NOT_CORRECT, array (
							GUI_DATE_FORMAT
					) );
					$all_data ['validfrom_error'] = 1;
					$valid_data = false;
				}
				if (! dateIsValid( $all_data ['validtil'] )) {
					add_error( ErrorCode::DATE_FORMAT_NOT_CORRECT, array (
							GUI_DATE_FORMAT
					) );
					$all_data ['validtil_error'] = 1;
					$valid_data = false;
				}

				if ($valid_data === true) {
					if ($capitalsourceid == 0)
						$ret = CapitalsourceControllerHandler::getInstance()->createCapitalsource( $all_data );
					else
						$ret = CapitalsourceControllerHandler::getInstance()->updateCapitalsource( $all_data );

					if ($ret === true) {
						$this->template->assign( 'CLOSE', 1 );
						break;
					} else {
						foreach ( $ret ['errors'] as $validationResult ) {
							$error = $validationResult ['error'];

							add_error( $error );

							switch ($error) {
								case ErrorCode::VALIDFROM_AFTER_VALIDTIL :
									$all_data ['validfrom_error'] = 1;
									$all_data ['validtil_error'] = 1;
									break;
								case ErrorCode::NAME_ALREADY_EXISTS :
									$all_data ['comment_error'] = 1;
									break;
								case ErrorCode::BANK_CODE_NOT_A_NUMBER :
								case ErrorCode::BANK_CODE_TO_LONG :
									$all_data ['bankcode_error'] = 1;
									break;
								case ErrorCode::ACCOUNT_NUMBER_NOT_A_NUMBER :
								case ErrorCode::ACCOUNT_NUMBER_TO_LONG :
									$all_data ['accountnumber_error'] = 1;
									break;
							}
						}
					}
				}
			default :
				if (! is_array( $all_data )) {
					if ($capitalsourceid > 0) {
						$all_data = CapitalsourceControllerHandler::getInstance()->showEditCapitalsource( $capitalsourceid );
						if (! is_array( $all_data )) {
							unset( $capitalsourceid );
						}
					} else {
						$all_data ['validfrom'] = convert_date_to_gui( date( 'Y-m-d' ) );
						$all_data ['validtil'] = convert_date_to_gui( MAX_YEAR );
					}
				}
				$type_values = $this->coreText->get_domain_data( 'CAPITALSOURCE_TYPE' );
				$state_values = $this->coreText->get_domain_data( 'CAPITALSOURCE_STATE' );

				$this->template->assign( 'TYPE_VALUES', $type_values );
				$this->template->assign( 'STATE_VALUES', $state_values );
				break;
		}

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'CAPITALSOURCEID', $capitalsourceid );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_edit_capitalsource.tpl' );
	}

	public final function display_delete_capitalsource($realaction, $capitalsourceid) {
		switch ($realaction) {
			case 'yes' :
				if (CapitalsourceControllerHandler::getInstance()->deleteCapitalsource( $capitalsourceid )) {
					$this->template->assign( 'CLOSE', 1 );
					break;
				}
			default :
				if ($capitalsourceid > 0) {
					$all_data = CapitalsourceControllerHandler::getInstance()->showDeleteCapitalsource( $capitalsourceid );
					if (is_array( $all_data )) {
						$all_data ['statecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_STATE', $all_data ['state'] );
						$all_data ['typecomment'] = $this->coreText->get_domain_meaning( 'CAPITALSOURCE_TYPE', $all_data ['type'] );
						$this->template->assign( 'ALL_DATA', $all_data );
					}
				}
				break;
		}

		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header( 1 );
		return $this->fetch_template( 'display_delete_capitalsource.tpl' );
	}
}
?>
