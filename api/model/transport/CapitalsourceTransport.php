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
// $Id: CapitalsourceTransport.php,v 1.4 2014/02/28 22:19:47 olivleh1 Exp $
//
namespace api\model\transport;

class CapitalsourceTransport {
	public $id;
	public $userid;
	public $type;
	public $state;
	public $accountNumber;
	public $bankCode;
	public $comment;
	public $validTil;
	public $validFrom;
	public $groupUse;

	public final function setId( $id )
	{
		$this->id = $id;
	}

	public final function setUserid( $userid )
	{
		$this->userid = $userid;
	}

	public final function setType( $type )
	{
		$this->type = $type;
	}

	public final function setState( $state )
	{
		$this->state = $state;
	}

	public final function setAccountNumber( $accountNumber )
	{
		$this->accountNumber = $accountNumber;
	}

	public final function setBankCode( $bankCode )
	{
		$this->bankCode = $bankCode;
	}

	public final function setComment( $comment )
	{
		$this->comment = $comment;
	}

	public final function setValidTil( $validTil )
	{
		$this->validTil = $validTil;
	}

	public final function setValidFrom( $validFrom )
	{
		$this->validFrom = $validFrom;
	}

	public final function setGroupUse( $groupUse )
	{
		$this->groupUse = $groupUse;
	}

	public final function getId()
	{
		return $this->id;
	}

	public final function getUserid()
	{
		return $this->userid;
	}

	public final function getType()
	{
		return $this->type;
	}

	public final function getState()
	{
		return $this->state;
	}

	public final function getAccountNumber()
	{
		return $this->accountNumber;
	}

	public final function getBankCode()
	{
		return $this->bankCode;
	}

	public final function getComment()
	{
		return $this->comment;
	}

	public final function getValidTil() {
		return $this->validTil;
	}

	public final function getValidFrom() {
		return $this->validFrom;
	}

	public final function getGroupUse()
	{
		return $this->groupUse;
	}

}

?>