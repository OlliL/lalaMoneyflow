<?php

namespace client\util;

class utilTimer {
	var $timer;
	var $running;

	function mStart() {
		$this->running = 1;
		$this->timer = explode( ' ', microtime() );
	}

	function mStop() {
		$endtime = explode( ' ', microtime() );
		$this->timer = ($endtime [1] - $this->timer [1]) + ($endtime [0] - $this->timer [0]);
		$this->running = 0;
	}

	function mGetTime() {
		if ($this->running == 1)
			$this->mStop();
		return $this->timer;
	}

	function mGetTimeVerbose() {
		if ($this->running == 1)
			$this->mStop();
		return sprintf( 'elapsed: %03.6f seconds', $this->timer );
	}

	function mPrintTime($timer = 0) {
		global $iscached;
		global $confTimer;
		
		if ($timer === 0)
			$timer = $this->mGetTime();
		
		switch ($confTimer) {
			case 2 :
				printf( '<font color="#FF0000" style="font-family:verdana,sans-serif;font-size:9px;">elapsed: %03.6f seconds, %s', $timer, ' </font>' );
				break;
			case 1 :
				printf( '<!-- elapsed: %03.6f seconds, %s //-->', $timer, $cacharray [$iscached] );
				break;
			case 0 :
				break;
		}
	}
}

?>
