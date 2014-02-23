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
// $Id: doLogonResponse.php,v 1.5 2014/02/23 16:53:21 olivleh1 Exp $
//
namespace rest\api\model\session;

class doLogonResponse {
	public $userId;
	public $sessionId;
	public $settingDateFormat;
	public $settingDisplayedLanguage;
	public $permissionAdmin;
	public $attributeNew;

	public final function setUserId($userId) {
		$this->userId = $userId;
	}

	public final function setSessionId($sessionId) {
		$this->sessionId = $sessionId;
	}

	public final function getUserId() {
		return $this->userId;
	}

	public final function getSessionId() {
		return $this->sessionId;
	}

	public final function setSettingDateFormat($settingDateFormat) {
		$this->settingDateFormat = $settingDateFormat;
	}

	public final function setSettingDisplayedLanguage($settingDisplayedLanguage) {
		$this->settingDisplayedLanguage = $settingDisplayedLanguage;
	}

	public final function getSettingDateFormat() {
		return $this->settingDateFormat;
	}

	public final function getSettingDisplayedLanguage() {
		return $this->settingDisplayedLanguage;
	}

	public final function setPermissionAdmin($permissionAdmin) {
		$this->permissionAdmin = $permissionAdmin;
	}

	public final function setAttributeNew($attributeNew) {
		$this->attributeNew = $attributeNew;
	}

	public final function getPermissionAdmin() {
		return $this->permissionAdmin;
	}

	public final function getAttributeNew() {
		return $this->attributeNew;
	}
}

?>