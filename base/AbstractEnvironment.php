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
// $Id: AbstractEnvironment.php,v 1.4 2014/03/14 06:01:26 olivleh1 Exp $
//
namespace base;

abstract class AbstractEnvironment {
	private static $instance;
	private $environment;
	const BACKEND_ARRAY = 1;
	const BACKEND_SESSION = 2;

	private function __construct() {
	}

	protected static function getInstanceInternal($backend) {
		if (! isset( self::$instance )) {
			// $className = __CLASS__;
			$className = get_called_class();
			self::$instance = new $className();
			self::$instance->setBackend( $backend );
		}
		return self::$instance;
	}

	public final function __clone() {
		trigger_error( 'Cloning not supported', E_USER_ERROR );
	}

	public final function __wakeup() {
		trigger_error( 'Deserialisation not supported', E_USER_ERROR );
	}

	protected final function setBackend($backend) {
		switch ($backend) {
			case self::BACKEND_SESSION :
				$this->environment = & $_SESSION;
				break;
			case self::BACKEND_ARRAY :
			default :
				$this->environment = array ();
				break;
		}
	}

	protected final function getValue($key) {
		if (array_key_exists( $key, $this->environment ))
			return $this->environment [$key];
	}

	protected final function setValue($key, $value) {
		$this->environment [$key] = $value;
	}
}

?>
