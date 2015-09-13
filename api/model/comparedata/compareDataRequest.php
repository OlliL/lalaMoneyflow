<?php

//
// Copyright (c) 2014-2015 Oliver Lehmann <oliver@laladev.org>
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
// $Id: compareDataRequest.php,v 1.6 2015/09/13 17:43:12 olivleh1 Exp $
//
namespace api\model\comparedata;

class compareDataRequest {
	public $formatId;
	public $capitalSourceId;
	public $startDate;
	public $endDate;
	public $fileContents;

	public final function setFormatId($formatId) {
		$this->formatId = $formatId;
	}

	public final function setCapitalSourceId($capitalSourceId) {
		$this->capitalSourceId = $capitalSourceId;
	}

	public final function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	public final function setEndDate($endDate) {
		$this->endDate = $endDate;
	}

	public final function setFileContents($fileContents) {
		$this->fileContents = $fileContents;
	}

	public final function getFormatId() {
		return $this->formatId;
	}

	public final function getCapitalSourceId() {
		return $this->capitalSourceId;
	}

	public final function getStartDate() {
		return $this->startDate;
	}

	public final function getEndDate() {
		return $this->endDate;
	}

	public final function getFileContents() {
		return $this->fileContents;
	}
}

?>