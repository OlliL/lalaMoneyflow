<?php
#-
# Copyright (c) 2005-2006 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: corePreDefMoneyFlows.php,v 1.14 2007/07/22 16:32:05 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreContractPartners.php';

class corePreDefMoneyFlows extends core {

	function corePreDefMoneyFlows() {
		$this->core();
	}

	function count_all_data() {
		if ( $num=$this->select_col( 'SELECT count(*) FROM predefmoneyflows' ) ) {
			return $num;
		} else {
			return;
		}
	}

	function get_all_data() {
		return $this->select_rows( "SELECT id,calc_amount(amount,'OUT',userid,createdate) amount,capitalsourceid,contractpartnerid,comment FROM predefmoneyflows WHERE userid=".USERID." ORDER BY id" );
	}

	function get_valid_data( $date='' ) {
		$date = $this->make_date($date);
		return $this->select_rows( "SELECT a.id,calc_amount(a.amount,'OUT',a.userid,createdate) amount,a.capitalsourceid,a.contractpartnerid,a.comment FROM predefmoneyflows a, capitalsources b, contractpartners c WHERE a.capitalsourceid=b.capitalsourceid AND $date BETWEEN validfrom AND validtil AND a.contractpartnerid=c.id AND a.userid=".USERID." AND b.userid=a.userid AND c.userid=a.userid ORDER BY id" );
	}

	function get_id_data( $id ) {
		return $this->select_row( "SELECT id,calc_amount(amount,'OUT',userid,createdate) amount,capitalsourceid,contractpartnerid,comment FROM predefmoneyflows WHERE id=$id AND userid=".USERID );
	}

	function get_capitalsourceid( $id ) {
		return $this->select_col( "SELECT capitalsourceid FROM predefmoneyflows WHERE id=$id AND userid=".USERID );
	}

	function get_all_index_letters() {
		$coreContractPartners=new coreContractPartners();
		$temp=$this->select_cols( 'SELECT DISTINCT contractpartnerid FROM predefmoneyflows WHERE userid='.USERID );
		return $coreContractPartners->get_ids_index_letters( $temp );
	}

	function get_all_matched_data( $letter ) {
		$coreContractPartners=new coreContractPartners();
		$ids=$coreContractPartners->get_ids_matched_data( $letter );
		if( is_array( $ids ) ) {
			$idstring=implode( $ids, ',' );
			return $this->select_rows( "SELECT id,calc_amount(amount,'OUT',userid,createdate) amount,capitalsourceid,contractpartnerid,comment FROM predefmoneyflows WHERE contractpartnerid IN ($idstring) AND userid=".USERID." ORDER BY comment" );
		} else {
			return;
		}
	}


	function delete_predefmoneyflow( $id ) {
		return $this->delete_row( "DELETE FROM predefmoneyflows WHERE id=$id AND userid=".USERID." LIMIT 1" );
	}


	function update_predefmoneyflow( $id, $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		if( fix_amount( $amount ) ) {
			return $this->update_row( "UPDATE predefmoneyflows set amount=calc_amount('$amount','IN',".USERID.",createdate),capitalsourceid='$capitalsourceid',contractpartnerid='$contractpartnerid',comment='$comment' WHERE id=$id AND userid=".USERID );
		} else {
			return false;
		}
	}

	function add_predefmoneyflow( $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		if( fix_amount( $amount ) ) {
			return $this->insert_row( "INSERT INTO predefmoneyflows (userid,amount,capitalsourceid,contractpartnerid,comment) VALUES (".USERID.",calc_amount('$amount','IN',".USERID.",NOW()),'$capitalsourceid','$contractpartnerid','$comment')" );
		} else {
			return false;
		}
	}
}
