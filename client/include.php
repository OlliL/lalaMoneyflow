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
// $Id: include.php,v 1.32 2014/03/02 23:42:21 olivleh1 Exp $
//

// ########
// user defined variables
// ########

// defines which language is used to display the login screen
const LOGIN_FORM_LANGUAGE = 1;

// jpgraph is used for plotting trends
define( 'ENABLE_JPGRAPH', true );
const ENCODING = 'ISO-8859-15';
const ROOTDIR = '/mnt/files/www/sites/olli.homeip.net/htdocs/moneyflow/';
const HTTPFULSUBDIR = 'contrib/httpful/src/';

// How to Reach the REST-Server
const URLPREFIX = 'http://127.0.0.1/moneyflow/';
const SERVERPREFIX = 'server/';

// ########
// more or less system defined stuff following
// ########

// style how the timer information is printed out in debug mode (int 0-2)
$confTimer = 2;
// ini_set( "log_errors", 1 );
// ini_set( "error_log", "/tmp/php-error-client.log" );
// debug mode (boolean)
$money_debug = false;
$money_debug = true;

// default year for "valid til" columns when creating a new dataset
define( 'MAX_YEAR', '2999-12-31' );

function framework_autoload($className) {
	$fname = str_replace( '\\', DIRECTORY_SEPARATOR, $className ) . '.php';
	if (is_file( ROOTDIR . $fname )) {
		require (ROOTDIR . $fname);
	} else if (is_file( ROOTDIR . HTTPFULSUBDIR . $fname )) {
		require (ROOTDIR . HTTPFULSUBDIR . $fname);
	}
}
spl_autoload_register( 'framework_autoload' );

// Do never change this! It is needed to generate GMT-UNIX Timestamps needed to communicate with the server!
date_default_timezone_set( 'UTC' );

?>
