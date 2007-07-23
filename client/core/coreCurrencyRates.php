<?php
#-
# Copyright (c) 2006 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreCurrencyRates.php,v 1.2 2007/07/23 04:17:13 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreSettings.php';

class coreCurrencyRates extends core {

	function coreCurrencyRates() {
		$this->core();
	}

	function count_all_data() {
		if ( $num=$this->select_col( 'SELECT count(*)
		                                FROM currencyrates' ) ) {
			return $num;
		} else {
			return;
		}
	}
	function get_all_data() {
		return $this->select_rows( 'SELECT mcr.currencyid
		                                  ,mcu.currency
						  ,mcr.rate
						  ,mcr.validfrom
						  ,mcr.validtil
					      FROM currencyrates mcr
					          ,currencies    mcu
					     WHERE mcr.currencyid = mcu.id' );
	}

	function get_id_data( $currencyid, $validfrom ) {
		$validfrom = $this->make_date( $validfrom );
		return $this->select_row( "SELECT mcr.currencyid
		                                 ,mcu.currency
						 ,mcr.rate
						 ,mcr.validfrom
						 ,mcr.validtil
					     FROM currencyrates mcr
					         ,currencies    mcu
					    WHERE mcr.currencyid = mcu.id
					      AND mcr.currencyid = $currencyid
					      AND mcr.validfrom  = $validfrom
					    LIMIT 1" );
	}

	function get_all_matched_data( $letter ) {
		return $this->select_rows( "SELECT mcr.currencyid
		                                 ,mcu.currency
						 ,mcr.rate
						 ,mcr.validfrom
						 ,mcr.validtil
					     FROM currencyrates mcr
					         ,currencies    mcu
					    WHERE mcr.currencyid    = mcu.id
					      AND UPPER(mcu.currency) LIKE UPPER('$letter%')
					    ORDER BY mcu.currency" );
	}

	function update_currencyrate( $_currencyid, $_validfrom, $currencyid, $rate ) {
		$_validfrom = $this->make_date( $_validfrom );
		return $this->update_row( "UPDATE currencyrates
		                              SET currencyid='$currencyid'
					         ,rate=$rate
					    WHERE currencyid = $_currencyid
					      AND validfrom  = $_validfrom" );
	}

	function add_currencyrate( $currencyid, $validfrom, $validtil, $rate ) {
		$validfrom = $this->make_date( $validfrom );
		$validtil  = $this->make_date( $validtil );
		/* check if there is a currencyrate for this currencyid which is valid at the
		 * desired valid from date - if that is the case, the existing dataset has to
		 * be terminated
		 */
		$num = $this->select_col( "SELECT count(*)
		                             FROM currencyrates
					    WHERE currencyid=$currencyid
					      AND $validfrom BETWEEN validfrom AND validtil
					    LIMIT 1" );
		if( $num == 1 ) {
			$this->update_row( "UPDATE currencyrates
			                       SET validtil=DATE_ADD($validfrom, INTERVAL -1 DAY)
					     WHERE currencyid = $currencyid
					       AND $validfrom BETWEEN validfrom AND validtil
					     LIMIT 1" );
		}
		return $this->update_row( "INSERT INTO currencyrates
		                                 (currencyid
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

	function delete_currencyrate( $currencyid, $validfrom ) {
		$validfrom = $this->make_date( $validfrom );
		/* when delete a currencyrate, make sure the most recent currencyrate left for
		 * this currencyid becomes valid.
		 */
		$num = $this->select_col( "SELECT count(*)
		                             FROM currencyrates
					    WHERE currencyid=$currencyid
					      AND $validfrom BETWEEN validfrom AND validtil
					    LIMIT 1" );
		if( $num == 1 ) {
			$this->update_row( "UPDATE currencyrates
			                       SET validtil='2999-12-31'
					     WHERE currencyid=$currencyid
					       AND DATE_ADD($validfrom, INTERVAL -1 DAY) BETWEEN validfrom AND validtil
					     LIMIT 1" );
		}		
		return $this->delete_row( "DELETE FROM currencyrates
		                            WHERE currencyid=$currencyid
					      AND validfrom=$validfrom
					    LIMIT 1" );
	}
}
