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
// $Id: showMonthlyReportGraphRequest.php,v 1.3 2015/02/13 00:03:40 olivleh1 Exp $
//
namespace api\model\report;

class showMonthlyReportGraphRequest {
	public $postingAccountIdsYes;
	public $postingAccountIdsNo;
	public $startDate;
	public $endDate;

	public final function setPostingAccountIdsYes(array $postingAccountIdsYes) {
		$this->postingAccountIdsYes = $postingAccountIdsYes;
	}

	public final function getPostingAccountIdsYes() {
		return $this->postingAccountIdsYes;
	}

	public final function setPostingAccountIdsNo(array $postingAccountIdsNo) {
		$this->postingAccountIdsNo = $postingAccountIdsNo;
	}

	public final function getPostingAccountIdsNo() {
		return $this->postingAccountIdsNo;
	}

	public final function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	public final function setEndDate($endDate) {
		$this->endDate = $endDate;
	}

	public final function getStartDate() {
		return $this->startDate;
	}

	public final function getEndDate() {
		return $this->endDate;
	}
}
?>