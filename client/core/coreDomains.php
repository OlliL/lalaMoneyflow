<?php
//
// Copyright (c) 2007-2013 Oliver Lehmann <oliver@FreeBSD.org>
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
// $Id: coreDomains.php,v 1.7 2013/08/11 17:04:55 olivleh1 Exp $
//
require_once 'core/core.php';

class coreDomains extends core {
	private $coreText;

	public final function __construct() {
		parent::__construct();
		$this->coreText = new coreText();
	}

	private final function getDomain($domain) {
		return apc_fetch( 'lalaMoneyflowDomains#' . $domain );
	}

	public final function get_domain_data($domain) {
		$ids = self::getDomain( $domain );
		if (is_array( $ids )) {
			$i = 0;
			foreach ( $ids as $key => $id ) {
				$retval [$i] ['value'] = $key;
				$retval [$i] ['text'] = $this->coreText->get_text( $id, 'd' );
				$i ++;
			}
		}
		return $retval;
	}

	public final function get_domain_meaning($domain, $value) {
		$ids = self::getDomain( $domain );
		return $this->coreText->get_text( $ids [$value], 'd' );
	}
}
