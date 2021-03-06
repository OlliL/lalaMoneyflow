<?php

//
// Copyright (c) 2013-2021 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: AbstractArrayMapper.php,v 1.11 2015/08/15 20:49:31 olivleh1 Exp $
//
namespace client\mapper;

use client\util\DateUtil;
use base\AbstractMapper;

abstract class AbstractArrayMapper extends AbstractMapper {

	protected static final function convertClientDateToTransport($clientDate) {
		return DateUtil::convertClientDateToTransport( $clientDate );
	}

	protected static final function convertTransportDateToClient($transportDate) {
		return DateUtil::convertTransportDateToClient( $transportDate );
	}

	protected static final function convertTransportTimestampToClient($transportDate) {
		return DateUtil::convertTransportTimestampToClient( $transportDate );
	}

	protected static final function convertClientTimestampWithMillisToTransport($clientDate) {
		return DateUtil::convertClientTimestampWithMillisToTransport( $clientDate );
	}

	protected static final function extractNanoSecondsFromClientDate($clientDate) {
		return DateUtil::extractNanoSecondsFromClientDate( $clientDate );
	}

}

?>