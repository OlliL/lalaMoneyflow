<?php
//
// Copyright (c) 2005-2015 Oliver Lehmann <oliver@laladev.org>
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
// $Id: include.php,v 1.38 2015/02/13 00:03:41 olivleh1 Exp $
//

//
// ATTENTION: you should leave this file unmodified!
//
define( 'ROOTDIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR );
define( 'HTTPFULDIR', ROOTDIR . 'contrib/httpful/src/' );

function framework_autoload($className) {
	$fname = str_replace( '\\', DIRECTORY_SEPARATOR, $className ) . '.php';
	if (is_file( ROOTDIR . $fname )) {
		require (ROOTDIR . $fname);
	} else if (is_file( HTTPFULDIR . $fname )) {
		require (HTTPFULDIR . $fname);
	}
}
spl_autoload_register( 'framework_autoload' );

// Do never change this! It is needed to generate GMT-UNIX Timestamps needed to communicate with the server!
date_default_timezone_set( 'UTC' );
ini_set( "log_errors", 1 );
ini_set( "error_log", "/tmp/php-error-client.log" );

define( 'ENABLE_JPGRAPH', true );
$money_debug=2;
?>
