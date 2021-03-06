<?php

//
// Copyright (c) 2013-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: ArrayToAccessRelationTransportMapper.php,v 1.6 2015/03/27 20:37:39 olivleh1 Exp $
//
namespace client\mapper;

use api\model\transport\AccessRelationTransport;

class ArrayToAccessRelationTransportMapper extends AbstractArrayMapper {

	public static function mapAToB(array $a) {
		$b = new AccessRelationTransport();
		if (array_key_exists( 'id', $a ))
			$b->setId( $a ['id'] );
		$b->setRefId( $a ['ref_id'] );

		$validfrom = parent::convertClientDateToTransport( $a ['validfrom'] );
		if ($validfrom)
			$b->setValidFrom( $validfrom );

		if (array_key_exists( 'validtil', $a )) {
			$validtil = parent::convertClientDateToTransport( $a ['validtil'] );
			if ($validtil)
				$b->setValidTil( $validtil );
		}
		return $b;
	}

	public static function mapBToA(AccessRelationTransport $b) {
		$a ['id'] = $b->getId();
		$a ['ref_id'] = $b->getRefId();
		$a ['validfrom'] = parent::convertTransportDateToClient( $b->getValidFrom() );
		$a ['validtil'] = parent::convertTransportDateToClient( $b->getValidTil() );
		$a ['validfrom_sort'] = $b->getValidfrom();

		return $a;
	}
}

?>