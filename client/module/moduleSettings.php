<?php
//
// Copyright (c) 2006-2014 Oliver Lehmann <oliver@FreeBSD.org>
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
// $Id: moduleSettings.php,v 1.23 2014/02/16 14:43:14 olivleh1 Exp $
//
require_once 'module/module.php';
require_once 'core/coreLanguages.php';
require_once 'core/coreUsers.php';
require_once 'core/coreSession.php';
require_once 'core/coreSettings.php';

class moduleSettings extends module {

	function moduleSettings() {
		parent::__construct();
		$this->coreLanguages = new coreLanguages();
		$this->coreUsers = new coreUsers();
		$this->coreSession = new coreSession();
		$this->coreSettings = new coreSettings();
	}

	function general_settings($data_is_valid, $userid, $realaction, $all_data) {
		global $GUI_LANGUAGE;
		switch ($realaction) {
			case 'save' :
				if ($data_is_valid === true) {
					if ($all_data ['date_data1'] == $all_data ['date_data2'] || $all_data ['date_data1'] == $all_data ['date_data3'] || $all_data ['date_data2'] == $all_data ['date_data4']) {
						$data_is_valid = false;
						add_error( 180 );
					} else {
						$this->coreSettings->set_displayed_language( $userid, $all_data ['language'] );
						$this->coreSettings->set_max_rows( $userid, $all_data ['maxrows'] );
						$this->coreSettings->set_num_free_moneyflows( $userid, $all_data ['numflows'] );
						$dateformat = $all_data ['date_data1'] . $all_data ['date_delimiter1'] . $all_data ['date_data2'] . $all_data ['date_delimiter2'] . $all_data ['date_data3'];
						$this->coreSettings->set_date_format( $userid, $dateformat );

						$this->coreSession->setAttribute( 'date_format', $dateformat );
						$this->coreSession->setAttribute( 'gui_language', $all_data ['language'] );
						define( GUI_DATE_FORMAT, $dateformat );
						$GUI_LANGUAGE = $all_data ['language'];
					}
				}
				break;
			default :
				break;
		}

		if ($data_is_valid === true) {
			$all_data ['language'] = $this->coreSettings->get_displayed_language( $userid );
			$all_data ['maxrows'] = $this->coreSettings->get_max_rows( $userid );
			$all_data ['numflows'] = $this->coreSettings->get_num_free_moneyflows( $userid );
			$dateformat = $this->coreSettings->get_date_format( $userid );
			$all_data = array_merge( $all_data, $dateformat );
		}

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'LANGUAGE_VALUES', $this->coreLanguages->get_all_data() );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header();
	}

	function display_personal_settings($realaction, $all_data) {
		$data_is_valid = true;

		switch ($realaction) {
			case 'save' :
				$user = LoggedOnUser::getInstance()->getUser();
				if ($user ['att_new'] && (empty( $all_data ['password1'] ) && empty( $all_data ['password2'] ))) {
					add_error( 152 );
					$data_is_valid = false;
				} elseif ($all_data ['password1'] != $all_data ['password2']) {
					add_error( 137 );
					$data_is_valid = false;
				} elseif (! empty( $all_data ['password1'] )) {
					$this->coreUsers->set_password( USERID, $all_data ['password1'] );
				}
				break;
			default :
				break;
		}

		$this->general_settings( $data_is_valid, USERID, $realaction, $all_data );

		return $this->fetch_template( 'display_personal_settings.tpl' );
	}

	function display_system_settings($realaction, $all_data) {
		$this->general_settings( true, 0, $realaction, $all_data );

		return $this->fetch_template( 'display_system_settings.tpl' );
	}
}
?>
