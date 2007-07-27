<?php
#-
# Copyright (c) 2006-2007 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreCurrencies.php,v 1.13 2007/07/27 06:42:26 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreSettings.php';

class coreCurrencies extends core {

	function coreCurrencies() {
		$this->core();
		$this->coreSettings = new coreSettings();
	}

	function count_all_data() {
		if ( $num = $this->select_col( 'SELECT count(*)
						  FROM currencies' ) ) {
			return $num;
		} else {
			return;
		}
	}
	function get_default_id() {
		return $this->select_col( '	SELECT currencyid
						  FROM currencies
						 WHERE att_default = 1
						 LIMIT 1' );
	}
	function get_all_data() {
		return $this->select_rows( '	SELECT currencyid
						      ,currency
						      ,att_default
						  FROM currencies' );
	}

	function get_id_data( $id ) {
		return $this->select_row( "	SELECT currencyid
						      ,currency
						      ,att_default
						  FROM currencies
						 WHERE currencyid = $id
						 LIMIT 1" );
	}

	function get_all_index_letters() {
		return $this->select_cols( '	SELECT DISTINCT UPPER(SUBSTR(currency,1,1)) letters
						  FROM currencies
						 ORDER BY letters' );
	}

	function get_all_matched_data( $letter ) {
		return $this->select_rows( "	SELECT currencyid
						      ,currency
						      ,att_default
						  FROM currencies
						 WHERE UPPER(currency) LIKE UPPER('$letter%')
						 ORDER BY currency" );
	}

	function add_currency( $currency, $att_default ) {
		$default_id = $this->get_default_id();
		
		if( $att_default == 1 && !empty($default_id) ) {
			$this->update_row( "	UPDATE currencies
						   SET att_default = 0
						 WHERE currencyid  = $default_id
						 LIMIT 1" );
		} elseif ($att_default == 0 && empty($default_id) ) {
			add_error( 144 );
			return;
		}
		return $this->update_row( "	INSERT INTO currencies
						      (currency
						      ,att_default
						      )
						       VALUES
						      ('$currency'
						      ,'$att_default'
						      )" );
	}

	function update_currency( $id, $currency, $att_default ) {
		$default_id = $this->get_default_id();
		
		if( $att_default == 1 && $default_id != $id ) {
			$this->update_row( "	UPDATE currencies
						   SET att_default = 0
						 WHERE currencyid  = $default_id
						 LIMIT 1" );
		} elseif ($att_default == 0 && $default_id == $id ) {
			add_error( 144 );
			return;
		}
		return $this->update_row( "	UPDATE currencies
						   SET currency    = '$currency'
						      ,att_default = '$att_default'
						 WHERE currencyid  = $id
						 LIMIT 1");
	}

	function delete_currency( $id ) {
		$default_id = $this->get_default_id();
		
		if( $default_id == $id ) {
			add_error( 145 );
			return false;
		}
		return $this->update_row( "	DELETE FROM currencies
						 WHERE currencyid = $id
						 LIMIT 1" );
	}

	function get_displayed_currency() {
		$id=$this->coreSettings->get_displayed_currency( USERID );
		if( !empty( $id ) ) {
			$currency=$this->get_currency( $id );
			if( !empty( $currency ) ) {
				return $currency;
			} else {
				add_error( 125 );
			}
		} else {
			add_error( 126 );
		}
	}

	function get_currency( $id ) {
		if( !empty( $id ) ) {
			return $this->select_col( "	SELECT currency
							  FROM currencies
							 WHERE currencyid = $id
							 LIMIT 1" );
		} else {
			return;
		}
	}
}
