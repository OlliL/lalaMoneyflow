<?php

//
// Copyright (c) 2014-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: ReportTurnoverCapitalsourceTransport.php,v 1.5 2015/08/14 22:07:44 olivleh1 Exp $
//
namespace api\model\transport;

class ReportTurnoverCapitalsourceTransport extends AbstractTransport {
	public $capitalsourceType;
	public $capitalsourceState;
	public $capitalsourceComment;
	public $amountBeginOfMonthFixed;
	public $amountEndOfMonthFixed;
	public $amountEndOfMonthCalculated;
	public $amountCurrent;
	public $amountCurrentState;

	public final function setCapitalsourceType($capitalsourceType) {
		$this->capitalsourceType = $capitalsourceType;
	}

	public final function setCapitalsourceState($capitalsourceState) {
		$this->capitalsourceState = $capitalsourceState;
	}

	public final function setCapitalsourceComment($capitalsourceComment) {
		$this->capitalsourceComment = $capitalsourceComment;
	}

	public final function setAmountBeginOfMonthFixed($amountBeginOfMonthFixed) {
		$this->amountBeginOfMonthFixed = $amountBeginOfMonthFixed;
	}

	public final function setAmountEndOfMonthFixed($amountEndOfMonthFixed) {
		$this->amountEndOfMonthFixed = $amountEndOfMonthFixed;
	}

	public final function setAmountEndOfMonthCalculated($amountEndOfMonthCalculated) {
		$this->amountEndOfMonthCalculated = $amountEndOfMonthCalculated;
	}

	public final function getCapitalsourceType() {
		return $this->capitalsourceType;
	}

	public final function getCapitalsourceState() {
		return $this->capitalsourceState;
	}

	public final function getCapitalsourceComment() {
		return $this->capitalsourceComment;
	}

	public final function getAmountBeginOfMonthFixed() {
		return $this->amountBeginOfMonthFixed;
	}

	public final function getAmountEndOfMonthFixed() {
		return $this->amountEndOfMonthFixed;
	}

	public final function getAmountEndOfMonthCalculated() {
		return $this->amountEndOfMonthCalculated;
	}

	public final function setAmountCurrent($amountCurrent) {
		$this->amountCurrent = $amountCurrent;
	}

	public final function getAmountCurrent() {
		return $this->amountCurrent;
	}

	public final function setAmountCurrentState($amountCurrentState) {
		$this->amountCurrentState = $amountCurrentState;
	}

	public final function getAmountCurrentState() {
		return $this->amountCurrentState;
	}
}

?>