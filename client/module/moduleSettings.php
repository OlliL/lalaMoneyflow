<?php
//
// Copyright (c) 2006-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: moduleSettings.php,v 1.30 2014/03/01 00:48:59 olivleh1 Exp $
//
namespace client\module;

use client\handler\SettingControllerHandler;
use base\ErrorCode;
use client\core\coreLanguages;
use client\core\coreSession;

class moduleSettings extends module {
	private $coreLanguages;
	private $coreSession;

	public final function __construct() {
		parent::__construct();
		$this->coreLanguages = new coreLanguages();
		$this->coreSession = new coreSession();
	}

	public final function display_personal_settings($realaction, $all_data) {
		switch ($realaction) {
			case 'save' :
				$data_is_valid = true;
				if ($all_data ['date_data1'] == $all_data ['date_data2'] || $all_data ['date_data1'] == $all_data ['date_data3'] || $all_data ['date_data2'] == $all_data ['date_data3']) {
					$data_is_valid = false;
					add_error( ErrorCode::INVALID_DATE_FORMAT_CHOOSEN );
				}
				if ($all_data ['password'] != $all_data ['password2']) {
					add_error( ErrorCode::PASSWORD_NOT_MATCHING );
					$data_is_valid = false;
				}

				if ($data_is_valid === true) {
					$all_data ['dateformat'] = $all_data ['date_data1'] . $all_data ['date_delimiter1'] . $all_data ['date_data2'] . $all_data ['date_delimiter2'] . $all_data ['date_data3'];
					SettingControllerHandler::getInstance()->updatePersonalSettings( $all_data );
					$this->coreSession->setAttribute( 'date_format', $all_data ['dateformat'] );
					$this->coreSession->setAttribute( 'gui_language', $all_data ['language'] );
					parent::setGuiLanguage( $all_data ['language'] );
					$this->coreSession->removeAttribute( 'att_new' );
				}
			default :
				if (! is_array( $all_data )) {

					$showPersonalSettings = SettingControllerHandler::getInstance()->showPersonalSettings();
					$all_data = array_merge( $showPersonalSettings, $this->convertDateFormatSetting( $showPersonalSettings ['dateformat'] ) );
				}
				;
				break;
		}

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'LANGUAGE_VALUES', $this->coreLanguages->get_all_data() );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header();

		return $this->fetch_template( 'display_personal_settings.tpl' );
	}

	public final function display_system_settings($realaction, $all_data) {
		switch ($realaction) {
			case 'save' :
				$data_is_valid = true;
				if ($all_data ['date_data1'] == $all_data ['date_data2'] || $all_data ['date_data1'] == $all_data ['date_data3'] || $all_data ['date_data2'] == $all_data ['date_data3']) {
					$data_is_valid = false;
					add_error( ErrorCode::INVALID_DATE_FORMAT_CHOOSEN );
				}

				if ($data_is_valid === true) {
					$all_data ['dateformat'] = $all_data ['date_data1'] . $all_data ['date_delimiter1'] . $all_data ['date_data2'] . $all_data ['date_delimiter2'] . $all_data ['date_data3'];
					SettingControllerHandler::getInstance()->updateDefaultSettings( $all_data );
				}
			default :
				if (! is_array( $all_data )) {

					$showDefaultSettings = SettingControllerHandler::getInstance()->showDefaultSettings();
					if (is_array( $showDefaultSettings )) {
						$dateformat = $showDefaultSettings ['dateformat'];
						$all_data = array_merge( $showDefaultSettings, $this->convertDateFormatSetting( $dateformat ) );
					}
				}
				;
				break;
		}

		$this->template->assign( 'ALL_DATA', $all_data );
		$this->template->assign( 'LANGUAGE_VALUES', $this->coreLanguages->get_all_data() );
		$this->template->assign( 'ERRORS', $this->get_errors() );

		$this->parse_header();

		return $this->fetch_template( 'display_system_settings.tpl' );
	}

	private final function convertDateFormatSetting($dateformat) {
		$patterns [0] = '/YYYY/';
		$patterns [1] = '/MM/';
		$patterns [2] = '/DD/';

		$replacements [0] = '';
		$replacements [1] = '';
		$replacements [2] = '';

		$delimiter = preg_replace( $patterns, $replacements, $dateformat );

		$ret ['date_delimiter1'] = substr( $delimiter, 0, 1 );
		$ret ['date_delimiter2'] = substr( $delimiter, 1, 1 );

		$pos_delimiter1 = strpos( $dateformat, $ret ['date_delimiter1'] );
		$pos_delimiter2 = strpos( substr( $dateformat, $pos_delimiter1 + 1 ), $ret ['date_delimiter2'] ) + $pos_delimiter1 + 1;

		$ret ['date_data1'] = substr( $dateformat, 0, $pos_delimiter1 );
		$ret ['date_data2'] = substr( $dateformat, $pos_delimiter1 + 1, $pos_delimiter2 - $pos_delimiter1 - 1 );
		$ret ['date_data3'] = substr( $dateformat, $pos_delimiter2 + 1 );

		return $ret;
	}
}
?>
