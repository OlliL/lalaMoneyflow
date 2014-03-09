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
// $Id: showMonthlyReportGraphResponse.php,v 1.1 2014/03/08 00:24:13 olivleh1 Exp $
//
namespace api\model\report;

class showMonthlyReportGraphResponse {
	public $postingAccountAmountTransport;
	public $postingAccountTransport;

	public final function getPostingAccountTransport() {
		return $this->postingAccountTransport;
	}

	public final function setPostingAccountTransport(array $postingAccountTransport) {
		$this->postingAccountTransport = $postingAccountTransport;
	}

	public final function getPostingAccountAmountTransport() {
		return $this->postingAccountAmountTransport;
	}

	public final function setPostingAccountAmountTransport(array $postingAccountAmountTransport) {
		$this->postingAccountAmountTransport = $postingAccountAmountTransport;
	}
}
?>