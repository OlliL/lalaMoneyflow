<?php

//
// Copyright (c) 2013-2015 Oliver Lehmann <oliver@laladev.org>
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
// $Id: UserTransport.php,v 1.5 2015/02/13 00:03:39 olivleh1 Exp $
//
namespace api\model\transport;

class UserTransport extends AbstractTransport {
	public $id;
	public $userName;
	public $userPassword;
	public $userIsAdmin;
	public $userIsNew;
	public $userCanLogin;

	public final function setId($id) {
		$this->id = $id;
	}

	public final function setUserName($userName) {
		$this->userName = $userName;
	}

	public final function setUserPassword($userPassword) {
		$this->userPassword = $userPassword;
	}

	public final function setUserIsAdmin($userIsAdmin) {
		$this->userIsAdmin = $userIsAdmin;
	}

	public final function setUserIsNew($userIsNew) {
		$this->userIsNew = $userIsNew;
	}

	public final function setUserCanLogin($userCanLogin) {
		$this->userCanLogin = $userCanLogin;
	}

	public final function getId() {
		return $this->id;
	}

	public final function getUserName() {
		return $this->userName;
	}

	public final function getUserPassword() {
		return $this->userPassword;
	}

	public final function getUserIsAdmin() {
		return $this->userIsAdmin;
	}

	public final function getUserIsNew() {
		return $this->userIsNew;
	}

	public final function getUserCanLogin() {
		return $this->userCanLogin;
	}
}

?>