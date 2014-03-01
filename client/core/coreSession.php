<?php
//
// Copyright (c) 2006-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: coreSession.php,v 1.18 2014/03/01 00:48:59 olivleh1 Exp $
//
namespace client\core;

class coreSession extends core {

	public final function __construct() {
		parent::__construct();
	}

	public final function setAttribute($attribute, $value) {
		if (! session_id()) {
			if (! $this->start()) {
				return false;
			}
		}

		$_SESSION [$attribute] = $value;
		return true;
	}

	public final function getAttribute($attribute) {
		if (isset( $_SESSION [$attribute] )) {
			return $_SESSION [$attribute];
		} else {
			return false;
		}
	}

	public final function removeAttribute($attribute) {
		unset( $_SESSION [$attribute] );
	}

	public final function start() {
		if (! headers_sent()) {
			session_start();
			return true;
		}
		return false;
	}

	public final function restart() {
		if (! session_id()) {
			if (! $this->start()) {
				return false;
			}
		}
		session_regenerate_id( true );
		return true;
	}

	public final function destroy() {
		if (session_id()) {
			session_destroy();
		}
	}
}
?>
