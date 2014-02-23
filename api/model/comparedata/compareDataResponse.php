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
// $Id: compareDataResponse.php,v 1.3 2014/02/23 18:59:58 olivleh1 Exp $
//
namespace rest\api\model\comparedata;

use rest\api\model\transport\CompareDataTransport;
use rest\api\model\transport\CompareDataMatchingTransport;
use rest\api\model\transport\CompareDataWrongCapitalsourceTransport;
use rest\api\model\transport\CompareDataNotInFileTransport;
use rest\api\model\transport\CompareDataNotInDatabaseTransport;
use rest\api\model\transport\CapitalsourceTransport;
use rest\api\model\validation\validationResponse;

class compareDataResponse extends validationResponse{
	public $compareDataMatchingTransport;
	public $compareDataWrongCapitalsourceTransport;
	public $compareDataNotInFileTransport;
	public $compareDataNotInDatabaseTransport;
	public $capitalsourceTransport;

	public final function setCompareDataMatchingTransport(array $compareDataMatchingTransport) {
		$this->compareDataMatchingTransport = $compareDataMatchingTransport;
	}

	public final function setCompareDataWrongCapitalsourceTransport(array $compareDataWrongCapitalsourceTransport) {
		$this->compareDataWrongCapitalsourceTransport = $compareDataWrongCapitalsourceTransport;
	}

	public final function setCompareDataNotInFileTransport(array $compareDataNotInFileTransport) {
		$this->compareDataNotInFileTransport = $compareDataNotInFileTransport;
	}

	public final function setCompareDataNotInDatabaseTransport(array $compareDataNotInDatabaseTransport) {
		$this->compareDataNotInDatabaseTransport = $compareDataNotInDatabaseTransport;
	}

	public final function setCapitalsourceTransport(CapitalsourceTransport $capitalsourceTransport) {
		$this->capitalsourceTransport = $capitalsourceTransport;
	}

	public final function addCompareDataMatchingTransport(CompareDataMatchingTransport $compareDataMatchingTransport) {
		$this->compareDataMatchingTransport [] = $compareDataMatchingTransport;
	}

	public final function addCompareDataWrongCapitalsourceTransport(CompareDataWrongCapitalsourceTransport $compareDataWrongCapitalsourceTransport) {
		$this->compareDataWrongCapitalsourceTransport [] = $compareDataWrongCapitalsourceTransport;
	}

	public final function addCompareDataNotInFileTransport(CompareDataNotInFileTransport $compareDataNotInFileTransport) {
		$this->compareDataNotInFileTransport [] = $compareDataNotInFileTransport;
	}

	public final function addCompareDataNotInDatabaseTransport(CompareDataNotInDatabaseTransport $compareDataNotInDatabaseTransport) {
		$this->compareDataNotInDatabaseTransport [] = $compareDataNotInDatabaseTransport;
	}

	public final function getCompareDataMatchingTransport() {
		return $this->compareDataMatchingTransport;
	}

	public final function getCompareDataWrongCapitalsourceTransport() {
		return $this->compareDataWrongCapitalsourceTransport;
	}

	public final function getCompareDataNotInFileTransport() {
		return $this->compareDataNotInFileTransport;
	}

	public final function getCompareDataNotInDatabaseTransport() {
		return $this->compareDataNotInDatabaseTransport;
	}

	public final function getCapitalsourceTransport() {
		return $this->capitalsourceTransport;
	}
}
?>