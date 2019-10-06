<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>lalaMoneyflow: Checking your Setup...</title>

<style type="text/css">
all,body,table,a,td,option,input,select {
	font-size: 10px;
	font-family: Bitstream Vera Sans, Arial, Helvetica, sans-serif;
}

td {
	padding: 3px;
	font-weight: bold;
}

.contrastbgcolor {
	background-color: #B0C4DE;
}
</style>

</head>
<body>
	<center>
		<h1>Checking your Setup...</h1>
	</center>
	<br>
	<br>
	<table width="600" align="center">
<?php

include "base/Singleton.php";
include "base/Configuration.php";
use base\Configuration;

function echo_row($leftText, $rightText, $ok) {
	if ($leftText) {
		echo '<tr><td class="contrastbgcolor">' . $leftText . '</td><td class="contrastbgcolor" align="center">';
	} else {
		echo '<tr><td>&nbsp;</td><td align="center" class="contrastbgcolor">';
	}
	if ($ok === false) {
		echo '<font color="red">' . $rightText . '</font>';
	} else if ($ok === null) {
		echo '<font color="orange">' . $rightText . '</font>';
	} else {
		echo '<font color="green">' . $rightText . '</font>';
	}
	echo '</td></tr>';
}

function check_extension($extension, $text = null, $optional = false) {
	if ($text === null)
		$text = $extension;
	if (! extension_loaded( $extension )) {
		if ($optional)
			echo_row( 'checking for extension ' . $text, 'not installed', null );
		else
			echo_row( 'checking for extension ' . $text, 'not installed', false );
		return 1;
	} else {
		echo_row( 'checking for extension ' . $text, 'installed', true );
		return 0;
	}
}

function check_class($class, $text) {
	if (! class_exists( $class )) {
		echo_row( 'checking for ' . $text, 'not installed', false );
		return 1;
	} else {
		echo_row( 'checking for ' . $text, 'installed', true );
		return 0;
	}
}

function print_result($errors, $allowedErrorCount) {
	if ($errors <= $allowedErrorCount)
		echo echo_row( null, 'OK', true );
	else
		echo echo_row( null, 'not OK', false );
}

function print_headline($headline) {
	echo '<tr><td colspan="2" align="center">' . $headline . '</td><tr>';
}

/*
 * *************************************************************************************
 */

print_headline( 'Checking PHP, PEAR, PECL extensions (all needed)' );
$errors = 0;
$errors += check_extension( 'session' );
$errors += check_extension( 'hash' );
$errors += check_extension( 'curl' );
$errors += check_extension( 'json' );
$errors += check_extension( 'mbstring' );
$errors += check_extension( 'gd' );
$errors += check_extension( 'intl' );
$errors += check_extension( 'xml' );
print_result( $errors, 0 );

/*
 * *************************************************************************************
 */

print_headline( 'Checking additional requirements' );
$errors = 0;
@include 'Smarty.class.php';
$errors += check_class( 'Smarty', '<a href="http://www.smarty.net/">Smarty Template Engine</a>' );
print_result( $errors, 0 );

/*
 * *************************************************************************************
 */

print_headline( 'Checking client/client.properties' );
$errors = 0;
Configuration::getInstance()->readConfig( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'client/client.properties' );

$context = stream_context_create( array (
		'http' => array (
				'header' => 'Connection: close\r\n'
		)
) );
$serverurl = Configuration::getInstance()->getProperty( 'serverurl' );
$url = $serverurl;
$http_response_header = array ();
$result = @file_get_contents( $url, false, $context );
$parsed_url = parse_url( $url );
if (count( $http_response_header ) == 0) {
	echo_row( 'checking Property "serverurl"', 'Server "' . $parsed_url ['host'] . '" <br> not reachable', false );
	$errors ++;
//} elseif (strpos( $http_response_header [0], '404' ) > 0) {
//	echo_row( 'checking Property "serverurl"', 'Path "' . $parsed_url ['path'] . '" on <br> Server "' . $parsed_url ['host'] . '" <br> not found', false );
//	$errors ++;
//} elseif (strpos( $http_response_header [0], '200' ) > 0 && $result == 'OK') {
} else {
	echo_row( 'checking Property "serverurl"', 'OK', true );
//} else {
//	echo_row( 'checking Property "serverurl"', 'not OK', false );
//	$errors ++;
}
print_result( $errors, 0 );

/*
 * *************************************************************************************
 */

print_headline( 'Checking Client .htaccess' );
$errors = 0;

$root = str_replace( 'check.php', '', getenv( 'SCRIPT_NAME' ) );
$url = 'http://' . getenv( 'HTTP_HOST' ) . $root . 'client/client.properties';
$http_response_header = array ();
$result = @file_get_contents( $url, false, $context );
if (strpos( $http_response_header [0], '403' ) > 0) {
	echo_row( 'checking that WWW access to "client.properties" is forbidden', 'OK', true );
} else {
	echo_row( 'checking that WWW access to "client.properties" is forbidden', 'not OK', false );
	$errors ++;
}
print_result( $errors, 0 );

/*
 * *************************************************************************************
 */

print_headline( 'Checking Client directory permissions' );
$errors = 0;

$directories = array (
		'client/cache' => false,
		'client/templates_c' => false,
		'client/locale' => null,
		'client/locale/1.conf' => null,
		'client/locale/2.conf' => null,
		'client/locale/languages.conf' => null
);
$root = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
foreach ( $directories as $directory => $errorlevel ) {
	if (is_writable( $root . $directory )) {
		echo_row( 'check if file or directory "' . $directory . '" can be accessed for writing', 'OK', true );
	} else {
		if ($errorlevel === false) {
			$msg = 'not OK';
			$errors ++;
		} else {
			$msg = 'not OK<br>(for translations)';
		}
		echo_row( 'check if file or directory "' . $directory . '" can be accessed for writing', $msg, $errorlevel );
	}
}
print_result( $errors, 0 );

?>
</table>
</body>
