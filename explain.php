<?php
#-
# Copyright (c) 2007-2014 Oliver Lehmann <oliver@FreeBSD.org>
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions
# are met:
# 1. Redistributions of source code must retain the above copyright
#	notice, this list of conditions and the following disclaimer
# 2. Redistributions in binary form must reproduce the above copyright
#	notice, this list of conditions and the following disclaimer in the
#	documentation and/or other materials provided with the distribution.
#
# THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
# ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
# IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
# ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
# FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
# DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
# OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
# HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
# LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
# OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
# SUCH DAMAGE.
#
# $Id: explain.php,v 1.9 2014/01/26 12:24:47 olivleh1 Exp $
#

require_once 'include.php';
require_once 'DB.php';
require_once 'util/utilTimer.php';
$timer = new utilTimer();

if( $money_debug !== true )
	exit;

function print_query( $reslink ) {
	$header_printed = false;
	echo  "<table border=1>";
	while ( $val = $reslink->fetchrow( DB_FETCHMODE_ASSOC ) ) {
		if( $header_printed === false ) {
			echo "<tr>";
			foreach( $val as $key => $value ) {
				echo "<th>".$key."</th>";
			}
			echo "</tr>";
			$header_printed = true;
		}
	
		echo "<tr>";
		foreach( $val as $key => $value ) {
			echo "<td>".$value."&nbsp</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}

$db = DB::connect( $dsn,1 );
if( DB::isError( $db ) )
	 die( $db->getMessage() );

$query = stripslashes($_GET['query']);

echo "<h1>Query</h1>";
echo "<pre>$query</pre>";

$reslink = $db->query( "EXPLAIN $query" );
if( DB::isError( $reslink ) )
	die( $reslink->getMessage() );

echo "<h1>EXPLAIN</h1>";
print_query($reslink);

$timer->mStart();
$reslink = $db->query( $query );
if( DB::isError( $reslink ) )
	die( $reslink->getMessage() );

echo "<h1>result set</h1>";
$timer->mPrintTime();
print_query($reslink);

?>
