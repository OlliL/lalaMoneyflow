<?php
//
// Copyright (c) 2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: Environment.php,v 1.1 2014/03/01 19:32:34 olivleh1 Exp $
//
namespace client\util;

use base\AbstractEnvironment;

class Environment extends AbstractEnvironment {
	const USER_NAME = 'user_name';
	const USER_PASSWORD = 'user_password';
	const USER_ID = 'users_id';
	const USER_ATT_NEW = 'att_new';
	const USER_PERM_ADMIN = 'perm_admin';
	const SETTING_DATE_FORMAT = 'date_format';
	const SETTING_GUI_LANGUGAGE = 'gui_language';
	const EVENTS_SHOWN = 'events_shown';

	public static function getInstance() {
		return parent::getInstanceInternal( parent::BACKEND_SESSION );
	}

	public function setUserName($userName) {
		$this->setValue( $this::USER_NAME, $userName );
	}

	public function setUserPassword($userPassword) {
		$this->setValue( $this::USER_PASSWORD, $userPassword );
	}

	public function setUserId($userId) {
		$this->setValue( $this::USER_ID, $userId );
	}

	public function setSettingDateFormat($settingDateFormat) {
		$this->setValue( $this::SETTING_DATE_FORMAT, $settingDateFormat );
	}

	public function setSettingGuiLanguage($settingGuiLanguage) {
		$this->setValue( $this::SETTING_GUI_LANGUGAGE, $settingGuiLanguage );
	}

	public function setUserAttNew($userAttNew) {
		$this->setValue( $this::USER_ATT_NEW, $userAttNew );
	}

	public function setUserPermAdmin($userPermAdmin) {
		$this->setValue( $this::USER_PERM_ADMIN, $userPermAdmin );
	}

	public function setEventsShown($eventsShown) {
		$this->setValue( $this::EVENTS_SHOWN, $eventsShown );
	}

	public function getUserName() {
		return $this->getValue( $this::USER_NAME );
	}

	public function getUserPassword() {
		return $this->getValue( $this::USER_PASSWORD );
	}

	public function getUserId() {
		return $this->getValue( $this::USER_ID );
	}

	public function getSettingDateFormat() {
		return $this->getValue( $this::SETTING_DATE_FORMAT );
	}

	public function getSettingGuiLanguage() {
		return $this->getValue( $this::SETTING_GUI_LANGUGAGE );
	}

	public function getUserAttNew() {
		return $this->getValue( $this::USER_ATT_NEW );
	}

	public function getUserPermAdmin() {
		return $this->getValue( $this::USER_PERM_ADMIN );
	}

	public function getEventsShown() {
		return $this->getValue( $this::EVENTS_SHOWN );
	}
}

?>