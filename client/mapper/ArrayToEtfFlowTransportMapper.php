<?php

//
// Copyright (c) 2021 Oliver Lehmann <lehmann@ans-netz.de>
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
namespace client\mapper;

use api\model\transport\EtfFlowTransport;

class ArrayToEtfFlowTransportMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new EtfFlowTransport();
		$b->setEtfflowid( $a ['etfflowid'] );
		$b->setAmount( $a ['amount'] );
		$b->setPrice( $a ['price'] );
		$b->setIsin( $a ['isin'] );

		$timestamp = parent::convertClientTimestampWithMillisToTransport( $a ['date'] . ' ' . $a ['time'] );
		if ($timestamp)
			$b->setTimestamp( $timestamp );

		$nanoseconds = parent::extractNanoSecondsFromClientDate( $a ['date'] . ' ' . $a ['time'] );
		if ($nanoseconds !== null)
			$b->setNanoseconds( $nanoseconds );

		return $b;
	}

	public static function mapBToA(EtfFlowTransport $b) {
		$a ['isin'] = $b->getIsin();
		$nanoseconds = $b->getNanoseconds();
		$milliseconds = str_pad($nanoseconds / 1000000,3,"0",STR_PAD_LEFT);
		$a ['timestamp'] = parent::convertTransportTimestampToClient( $b->getTimestamp() ) . ":" . $milliseconds;
		$a ['etfflowid'] = $b->getEtfflowid();
		$a ['amount'] = $b->getAmount();
		$a ['price'] = $b->getPrice();

		return $a;
	}
}
?>