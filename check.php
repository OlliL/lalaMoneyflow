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
include "base/Configuration.php";
use base\Configuration;

function echo_row($leftText, $rightText, $ok) {
	if ($leftText) {
		echo '<tr><td class="contrastbgcolor">' . $leftText . '</td><td class="contrastbgcolor" align="center">';
	} else {
		echo '<tr><td>&nbsp;</td><td align="center" class="contrastbgcolor">';
	}
	if (! $ok) {
		echo '<font color="red">' . $rightText . '</font>';
	} else {
		echo '<font color="green">' . $rightText . '</font>';
	}
	echo '</td></tr>';
}

function check_extension($extension, $text = null) {
	if ($text === null)
		$text = $extension;
	if (! extension_loaded( $extension )) {
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
$errors += check_extension( 'xml' );
$errors += check_extension( 'curl' );
$errors += check_extension( 'PDO_MYSQL' );
$errors += check_extension( 'json' );
$errors += check_extension( 'mbstring' );
$errors += check_extension( 'ctype' );
$errors += check_extension( 'gd' );
$errors += check_extension( 'simplexml' );
$errors += check_extension( 'SPL_Types' );
print_result( $errors, 0 );

print_headline( 'Checking existing Cache Frameworks (minimum 1 needed)' );
$errors = 0;
$errors += check_extension( 'yac', '<a href="https://github.com/laruence/yac">yac</a>' );
$errors += check_extension( 'memcached' );
$errors += check_extension( 'redis' );
print_result( $errors, 2 );

/*
 * *************************************************************************************
 */

print_headline( 'Checking additional requirements' );
$errors = 0;
@include 'Smarty.class.php';
$errors += check_class( 'Smarty', '<a href="http://www.smarty.net/">Smarty Template Engine</a>' );
@include 'jpgraph.php';
$errors += check_class( 'Graph', '<a href="http://jpgraph.net/">JpGraph</a>' );
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
$url = Configuration::getInstance()->getProperty( 'serverurl' ) . 'admin/heartbeat';
$result = @file_get_contents( $url, false, $context );
if ($result != 'OK ') {
	echo_row( 'checking Property "serverurl"', 'not OK', false );
	$error ++;
} else {
	echo_row( 'checking Property "serverurl"', 'OK', true );
}
print_result( $errors, 0 );

/*
 * *************************************************************************************
 */

print_headline( 'Checking server/server.properties' );
$errors = 0;
Configuration::getInstance()->readConfig( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'server/server.properties' );

$driver = Configuration::getInstance()->getProperty( 'driver', 'DATABASE' );
if ($driver != 'mysql') {
	echo_row( 'checking Property "DATABASE:driver"', 'not OK', false );
	$error ++;
} else {
	echo_row( 'checking Property "DATABASE:driver"', 'OK', true );
}

$host = Configuration::getInstance()->getProperty( 'host', 'DATABASE' );
$user = Configuration::getInstance()->getProperty( 'user', 'DATABASE' );
$password = Configuration::getInstance()->getProperty( 'password', 'DATABASE' );
$link = @mysql_connect( $host, $user, $password );
if (! $link) {
	echo_row( 'checking Property "DATABASE:host", "DATABASE:user", "DATABASE:password"', 'not OK', false );
	$error ++;
} else {
	echo_row( 'checking Property "DATABASE:host", "DATABASE:user", "DATABASE:password"', 'OK', true );
}

$dbname = Configuration::getInstance()->getProperty( 'dbname', 'DATABASE' );
$db_selected = @mysql_select_db( $dbname, $link );
if (! $db_selected) {
	echo_row( 'checking Property "DATABASE:dbname"', 'not OK', false );
	$error ++;
} else {
	echo_row( 'checking Property "DATABASE:dbname"', 'OK', true );
}

$cache = Configuration::getInstance()->getProperty( 'driver', 'CACHE' );
if ($cache != 'REDIS' && $cache != 'MEMCACHED' && $cache != 'YAC' && $cache != 'NOOP') {
	echo_row( 'checking Property "CACHE:driver"', 'not OK', false );
	$error ++;
} else {
	echo_row( 'checking Property "CACHE:driver"', 'OK', true );
}

$cacheServer = Configuration::getInstance()->getProperty( 'server', 'CACHE' );
$cachePort = ( int ) Configuration::getInstance()->getProperty( 'port', 'CACHE' );
$result = null;
switch ($cache) {
	case 'REDIS' :
		$cacheDelegate = new \Redis();
		$result = $cacheDelegate->connect( $cacheServer, $cachePort );
		break;
	case 'MEMCACHED' :
		$cacheDelegate = new \Memcached();
		$result = $cacheDelegate->addServer( $cacheServer, $cachePort );
		$version = $cacheDelegate->getStats();
		$pid = array_values( $version )[0]['pid'];
		$result = $pid > 0 ? true : false;
		break;
}
if ($result !== null) {
	if (! $result) {
		echo_row( 'checking Property "CACHE:server", "CACHE:port"', 'not OK', false );
		$error ++;
	} else {
		echo_row( 'checking Property "CACHE:server", "CACHE:port"', 'OK', true );
	}
}
$serializer = Configuration::getInstance()->getProperty( 'driver', 'SERIALIZER' );
if ($serializer != 'IGBINARY' && $serializer != 'PHP') {
	echo_row( 'checking Property "SERIALIZER:driver"', 'not OK', false );
	$error ++;
} else {
	echo_row( 'checking Property "SERIALIZER:driver"', 'OK', true );
}

print_result( $errors, 0 );

/*
 * *************************************************************************************
 */

print_headline( 'Checking MySQL DB' );
$errors = 0;

$name_found = false;
if ($link) {
	$name = null;
	$sql = ('SELECT name FROM access WHERE id = 0');
	$result = mysql_query( $sql, $link );
	if ($result) {
		$row = mysql_fetch_row( $result );
		if (is_array( $row ) && count( $row ) === 1) {
			$name = $row [0];
		}
	}
	if ($name === 'root')
		$nameFound = true;
}
if (!$nameFound) {
	echo_row( 'checking MySQL Database', 'not OK', false );
	$error ++;
} else {
	echo_row( 'checking MySQL Database', 'OK', true );
}

// check that property files can not be accessed! (.htaccess in place)
// implement a heartbeat w/o auth in the rest server to check Slim
?>
</table>
</body>