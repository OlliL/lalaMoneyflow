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
# $Id: coreCurrencyRates.php,v 1.6 2007/07/27 06:42:26 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreSettings.php';

class coreCurrencyRates extends core {

	function coreCurrencyRates() {
		$this->core();
	}

	function count_all_data() {
		if ( $num=$this->select_col( '	SELECT count(*)
						  FROM currencyrates' ) ) {
			return $num;
		} else {
			return;
		}
	}

	function check_if_exists( $currencyid, $validfrom ) {
		$validfrom = $this->make_date( $validfrom );
		return $this->select_col( "	SELECT 1
						  FROM currencyrates
						 WHERE mcu_currencyid = $currencyid
						   AND validfrom      = $validfrom" );
	}

	function get_validtil( $currencyid, $validfrom ) {
		$validfrom = $this->make_date( $validfrom );
		return $this->select_col( "	SELECT validtil
						  FROM currencyrates
						 WHERE mcu_currencyid = $currencyid
						   AND validfrom      = $validfrom" );
	}

	function get_all_data() {
		return $this->select_rows( '	SELECT mcr.mcu_currencyid
						      ,mcu.currency
						      ,mcr.rate
						      ,mcr.validfrom
						      ,mcr.validtil
						      ,IF (mcr.validfrom <= NOW(),1,0) att_past
						  FROM currencyrates mcr
						      ,currencies    mcu
						 WHERE mcr.mcu_currencyid = mcu.currencyid
						 ORDER BY mcu.currency,mcr.validfrom' );
	}

	function get_id_data( $currencyid, $validfrom ) {
		$validfrom = $this->make_date( $validfrom );
		return $this->select_row( "	SELECT mcr.mcu_currencyid
						      ,mcu.currency
						      ,mcr.rate
						      ,mcr.validfrom
						      ,mcr.validtil
						  FROM currencyrates mcr
						      ,currencies    mcu
						 WHERE mcr.mcu_currencyid = mcu.currencyid
						   AND mcr.mcu_currencyid = $currencyid
						   AND mcr.validfrom      = $validfrom
						 LIMIT 1" );
	}

	function get_all_matched_data( $letter ) {
		return $this->select_rows( "	SELECT mcr.mcu_currencyid
						      ,mcu.currency
						      ,mcr.rate
						      ,mcr.validfrom
						      ,mcr.validtil
						      ,IF (mcr.validfrom <= NOW(),1,0) att_past
						  FROM currencyrates mcr
						      ,currencies    mcu
						 WHERE mcr.mcu_currencyid  = mcu.currencyid
						   AND UPPER(mcu.currency) LIKE UPPER('$letter%')
						 ORDER BY mcu.currency,mcr.validfrom" );
	}


	function update_currencyrate( $currencyid, $validfrom, $rate ) {
		$validfrom = $this->make_date( $validfrom );
		return $this->update_row( "	UPDATE currencyrates
						   SET rate           = $rate
						 WHERE mcu_currencyid = $currencyid
						   AND validfrom      = $validfrom" );
	}

	function add_currencyrate( $currencyid, $validfrom, $rate ) {
		/*
		   2 things need to be considered here
		   
		   1. The most recent currency rate before the new may have > validtil then the new validfrom
		      -> here we have to adjust the validtil of the existing rate

		   2. There might be another currencyrate definition after the new ones start date.
		      -> here we have to adjust the new validtil to the existing validfrom-1 day
		 */
		      

		/* check if there already exists a currencyrate with the validfrom date */

		if( $this->check_if_exists( $currencyid, $validfrom ) == 1 ) {
			add_error( 146 );
			return false;
		}

		$validfrom  = $this->make_date( $validfrom );
		$validtil   = $this->make_date( '2999-12-31' );


		/* 1st check */
		
		$num_prev = $this->select_col( "SELECT count(*)
						  FROM currencyrates
						 WHERE mcu_currencyid = $currencyid
						   AND $validfrom BETWEEN validfrom AND validtil
						 LIMIT 1" );

		if( $num_prev == 1 ) {
			$this->update_row( "	UPDATE currencyrates
						   SET validtil = DATE_ADD($validfrom, INTERVAL -1 DAY)
						 WHERE mcu_currencyid = $currencyid
						   AND $validfrom       BETWEEN validfrom AND validtil
						 LIMIT 1" );
		}

		/* 2nd check */
		
		$tildate = $this->select_col( "	SELECT validfrom
						  FROM currencyrates
						 WHERE mcu_currencyid = $currencyid
						   AND validfrom > $validfrom
						 ORDER BY validfrom ASC
						 LIMIT 1" );

		if( !empty( $tildate ) ) {
			$validtil = 'DATE_ADD('.$this->make_date( $tildate ).', INTERVAL -1 DAY)';
		}

		return $this->update_row( "	INSERT INTO currencyrates
						      (mcu_currencyid
						      ,rate
						      ,validfrom
						      ,validtil
						      )
						       VALUES
						      ($currencyid
						      ,$rate
						      ,$validfrom
						      ,$validtil
						      )" );
	}
}
