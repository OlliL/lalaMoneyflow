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
// $Id: updatePersonalSettingsRequest.php,v 1.2 2014/02/28 22:19:47 olivleh1 Exp $
//
namespace api\model\setting;

class updatePersonalSettingsRequest {
	public $language;
	public $dateFormat;
	public $maxRows;
	public $numFreeMoneyflows;
	public $password;

	public final function setLanguage($language) {
		$this->language = $language;
	}

	public final function setDateFormat($dateFormat) {
		$this->dateFormat = $dateFormat;
	}

	public final function setMaxRows($maxRows) {
		$this->maxRows = $maxRows;
	}

	public final function setNumFreeMoneyflows($numFreeMoneyflows) {
		$this->numFreeMoneyflows = $numFreeMoneyflows;
	}

	public final function setPassword($password) {
		$this->password = $password;
	}

	public final function getLanguage() {
		return $this->language;
	}

	public final function getDateFormat() {
		return $this->dateFormat;
	}

	public final function getMaxRows() {
		return $this->maxRows;
	}

	public final function getNumFreeMoneyflows() {
		return $this->numFreeMoneyflows;
	}

	public final function getPassword() {
		return $this->password;
	}
}

?>