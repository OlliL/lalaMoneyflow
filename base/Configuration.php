<?php

//
// Copyright (c) 2013-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: Configuration.php,v 1.1 2014/03/10 20:02:40 olivleh1 Exp $
//
namespace base;

class Configuration {
	private static $instance;
	private $configurationHolder;

	private function __construct() {
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			$className = __CLASS__;
			self::$instance = new $className();
		}
		return self::$instance;
	}

	public final function __clone() {
		trigger_error( 'Cloning not supported', E_USER_ERROR );
	}

	public final function __wakeup() {
		trigger_error( 'Deserialisation not supported', E_USER_ERROR );
	}

	public final function readConfig($filename) {
		$this->configurationHolder = parse_ini_file($filename,true);
	}

	public function getProperty($key, $section=null) {
		error_log($key.'-'.$section);
		if($section === null) {
			return $this->configurationHolder[$key];
		} else {
			return $this->configurationHolder[$section][$key];
		}
	}
}
?>