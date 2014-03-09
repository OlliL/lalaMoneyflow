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
	<center><h1>Checking your Setup...</h1></center><br><br>
	<table width="600" align="center">
<?php

function check_extension($extension, $text = null) {
	if ($text === null)
		$text = $extension;
	echo '<tr><td class="contrastbgcolor">checking for extension ' . $text . '</td><td class="contrastbgcolor" align="center">';
	if (! extension_loaded( $extension )) {
		echo '<font color="red">not installed</font>';
		return 1;
	} else {
		echo '<font color="green">installed</font>';
		return 0;
	}
}

function check_class($class, $text) {
	echo '<tr><td class="contrastbgcolor">checking for ' . $text . '</td><td class="contrastbgcolor" align="center">';
	if (! class_exists( $class )) {
		echo '<font color="red">not installed</font>';
		return 1;
	} else {
		echo '<font color="green">installed</font>';
		return 0;
	}
}

function print_result($errors, $allowedErrorCount) {
	echo '<tr><td>&nbsp;</td><td align="center" class="contrastbgcolor">';
	if ($errors <= $allowedErrorCount)
		echo '<font color="green">OK</font>';
	else
		echo '<font color="red">not OK</font>';
	echo '</td><tr>';
}

function print_headline($headline) {
	echo '<tr><td colspan="2" align="center">' . $headline . '</td><tr>';
}


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



print_headline( 'Checking additional requirements' );
$errors = 0;
@include 'Smarty.class.php';
$errors += check_class( 'Smarty', '<a href="http://www.smarty.net/">Smarty Template Engine</a>' );
@include 'jpgraph.php';
$errors += check_class( 'Graph', '<a href="http://jpgraph.net/">JpGraph</a>' );
print_result( $errors, 0 );

print_headline( 'Checking client/include.php' );
print_headline( 'Checking server/include.php' );
print_headline( 'Checking MySQL DB' );

?>
</table>
</body>