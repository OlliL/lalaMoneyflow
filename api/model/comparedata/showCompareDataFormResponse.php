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
// $Id: showCompareDataFormResponse.php,v 1.6 2016/12/30 18:34:53 olivleh1 Exp $
//
namespace api\model\comparedata;

class showCompareDataFormResponse {
	public $compareDataFormatTransport;
	public $capitalsourceTransport;
	public $selectedCapitalsourceId;
	public $selectedDataFormat;
	public $selectedSourceIsFile;

	public final function getCompareDataFormatTransport() {
		return $this->compareDataFormatTransport;
	}

	public final function setCompareDataFormatTransport(array $compareDataFormatTransport) {
		$this->compareDataFormatTransport = $compareDataFormatTransport;
	}

	public final function getCapitalsourceTransport() {
		return $this->capitalsourceTransport;
	}

	public final function setCapitalsourceTransport(array $capitalsourceTransport) {
		$this->capitalsourceTransport = $capitalsourceTransport;
	}

	public final function setSelectedCapitalsourceId($selectedCapitalsourceId) {
		$this->selectedCapitalsourceId = $selectedCapitalsourceId;
	}

	public final function setSelectedDataFormat($selectedDataFormat) {
		$this->selectedDataFormat = $selectedDataFormat;
	}

	public final function getSelectedCapitalsourceId() {
		return $this->selectedCapitalsourceId;
	}

	public final function getSelectedDataFormat() {
		return $this->selectedDataFormat;
	}

        public final function setSelectedSourceIsFile($selectedSourceIsFile) {
                $this->selectedSourceIsFile = $selectedSourceIsFile;
        }

        public final function getSelectedSourceIsFile() {
                return $this->selectedSourceIsFile;
        }

}

?>
