<?php

namespace rest\api\model\capitalsource\transport;

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

	public final function getValidTil()
	{
		return $this->validTil;
	}

	public final function getValidFrom()
	{
		return $this->validFrom;
	}

	public final function getGroupUse()
	{
		return $this->groupUse;
	}

}

?>