<?php
#-
# Copyright (c) 2006-2012 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreSettings.php,v 1.14 2012/01/19 21:25:07 olivleh1 Exp $
#

require_once 'core/core.php';

class coreSettings extends core {

	function coreSettings() {
		$this->core();
	}

	function get_value( $userid, $name ) {
		return $this->select_col( "	SELECT value
						  FROM settings
						 WHERE name       = '$name'
						   AND mur_userid = $userid
						 LIMIT 1" );
	}

	function set_value( $userid, $name, $value ) {
		return $this->insert_row( "	INSERT INTO settings
						           (mur_userid
						           ,name
						           ,value
						           )
						             VALUES
							   ($userid
							   ,'$name'
							   ,'$value'
							   )
							   ON DUPLICATE KEY UPDATE value = VALUES(value)");
	}

	function init_settings( $userid ) {
		return $this->insert_row( "	INSERT INTO settings
						           (mur_userid
						           ,name
						           ,value
						           )
						           (SELECT $userid
						                  ,name
						                  ,value
						              FROM settings
						             WHERE mur_userid = 0)
							   ON DUPLICATE KEY UPDATE value = VALUES(value)" );
	}

	function get_displayed_currency( $userid ) {
		return $this->get_value( $userid, 'displayed_currency' );
	}

	function get_displayed_language( $userid ) {
		return $this->get_value( $userid, 'displayed_language' );
	}

	function get_max_rows( $userid ) {
		return $this->get_value( $userid, 'max_rows' );
	}

	function get_compare_capitalsource( $userid ) {
		return $this->get_value( $userid, 'compare_capitalsource' );
	}

	function get_compare_format( $userid ) {
		return $this->get_value( $userid, 'compare_format' );
	}

	function get_trend_capitalsourceid( $userid ) {
		return unserialize($this->get_value( $userid, 'trend_capitalsourceid' ));
	}

	function get_date_format( $userid ) {
		$dateformat = $this->get_value( $userid, 'date_format' );

		$patterns[0] = '/YYYY/';
		$patterns[1] = '/MM/';
		$patterns[2] = '/DD/';
	
		$replacements[0] = '';
		$replacements[1] = '';
		$replacements[2] = '';
	
		$delimiter = preg_replace( $patterns, $replacements, $dateformat );
		
		$ret['date_delimiter1'] = substr( $delimiter, 0, 1 );
		$ret['date_delimiter2'] = substr( $delimiter, 1, 1 );
		
		$pos_delimiter1 = strpos( $dateformat, $ret['date_delimiter1'] );
		$pos_delimiter2 = strpos( substr( $dateformat, $pos_delimiter1+1 ), $ret['date_delimiter2'] )+$pos_delimiter1+1;

		$ret['date_data1'] = substr( $dateformat, 0, $pos_delimiter1 );
		$ret['date_data2'] = substr( $dateformat, $pos_delimiter1+1, $pos_delimiter2-$pos_delimiter1-1 );
		$ret['date_data3'] = substr( $dateformat, $pos_delimiter2+1 );
		
		$ret['dateformat'] = $dateformat;
		
		return $ret;

	}

	function get_num_free_moneyflows( $userid ) {
		return $this->get_value( $userid, 'num_free_moneyflows' );
	}

	function set_displayed_currency( $userid, $currency ) {
		return $this->set_value( $userid, 'displayed_currency', $currency );
	}

	function set_displayed_language( $userid, $language ) {
		return $this->set_value( $userid, 'displayed_language', $language );
	}

	function set_max_rows( $userid, $maxnum ) {
		return $this->set_value( $userid, 'max_rows', $maxnum );
	}

	function set_compare_capitalsource( $userid, $capitalsource ) {
		return $this->set_value( $userid, 'compare_capitalsource', $capitalsource );
	}

	function set_compare_format( $userid, $format ) {
		return $this->set_value( $userid, 'compare_format', $format );
	}

	function set_trend_capitalsourceid( $userid, $capitalsourceid ) {
		return $this->set_value( $userid, 'trend_capitalsourceid', serialize($capitalsourceid) );
	}

	function set_date_format( $userid, $dateformat ) {
		return $this->set_value( $userid, 'date_format', $dateformat );
	}

	function set_num_free_moneyflows( $userid, $num ) {
		return $this->set_value( $userid, 'num_free_moneyflows', $num );
	}
}

