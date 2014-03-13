<?php
//
// Copyright (c) 2005-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: utilTimer.php,v 1.7 2014/03/13 17:30:01 olivleh1 Exp $
//
namespace client\util;

class utilTimer {
	private $timer;
	private $running;
	private $confTimer;

	public final function __construct($confTimer) {
		$this->timer = explode( ' ', microtime() );
		$this->confTimer = $confTimer;
		$this->running = true;
	}

	public final function mStop() {
		$endtime = explode( ' ', microtime() );
		$this->timer = ($endtime [1] - $this->timer [1]) + ($endtime [0] - $this->timer [0]);
		$this->running = false;
	}

	private final function mGetTime() {
		if ($this->running)
			$this->mStop();
		return $this->timer;
	}

	public final function mPrintTime() {
		$timer = $this->mGetTime();

		switch ($this->confTimer) {
			case 2 :
				printf( '<font color="#FF0000" style="font-family:verdana,sans-serif;font-size:9px;">elapsed: %03.6f seconds, %s', $timer, ' </font>' );
				break;
			case 1 :
				printf( '<!-- elapsed: %03.6f seconds //-->', $timer );
				break;
			case 0 :
				break;
		}
	}
}

?>
