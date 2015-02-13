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
// $Id: ContractpartnerTransport.php,v 1.7 2015/02/13 00:03:39 olivleh1 Exp $
//
namespace api\model\transport;

class ContractpartnerTransport extends AbstractTransport {
	public $id;
	public $userid;
	public $name;
	public $street;
	public $postcode;
	public $town;
	public $validTil;
	public $validFrom;
	public $country;
	public $moneyflowComment;
	public $postingAccountName;
	public $postingAccountId;

	public final function setId($id) {
		$this->id = $id;
	}

	public final function setUserid($userid) {
		$this->userid = $userid;
	}

	public final function setName($name) {
		$this->name = $name;
	}

	public final function setStreet($street) {
		$this->street = $street;
	}

	public final function setPostcode($postcode) {
		$this->postcode = $postcode;
	}

	public final function setTown($town) {
		$this->town = $town;
	}

	public final function setCountry($country) {
		$this->country = $country;
	}

	public final function setValidTil($validTil) {
		$this->validTil = $validTil;
	}

	public final function setValidFrom($validFrom) {
		$this->validFrom = $validFrom;
	}

	public final function getId() {
		return $this->id;
	}

	public final function getUserid() {
		return $this->userid;
	}

	public final function getName() {
		return $this->name;
	}

	public final function getStreet() {
		return $this->street;
	}

	public final function getPostcode() {
		return $this->postcode;
	}

	public final function getTown() {
		return $this->town;
	}

	public final function getCountry() {
		return $this->country;
	}

	public final function getValidTil() {
		return $this->validTil;
	}

	public final function getValidFrom() {
		return $this->validFrom;
	}

	public final function getMoneyflowComment() {
		return $this->moneyflowComment;
	}

	public final function setMoneyflowComment($moneyflowComment) {
		$this->moneyflowComment = $moneyflowComment;
	}

	public final function getPostingAccountId() {
		return $this->postingAccountId;
	}

	public final function setPostingAccountId($postingAccountId) {
		$this->postingAccountId = $postingAccountId;
	}

	public final function getPostingAccountName() {
		return $this->postingAccountName;
	}

	public final function setPostingAccountName($postingAccountName) {
		$this->postingAccountName = $postingAccountName;
	}
}

?>