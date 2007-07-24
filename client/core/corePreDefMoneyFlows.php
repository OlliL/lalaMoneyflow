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
# $Id: corePreDefMoneyFlows.php,v 1.15 2007/07/24 18:22:06 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreContractPartners.php';

class corePreDefMoneyFlows extends core {

	function corePreDefMoneyFlows() {
		$this->core();
	}

	function count_all_data() {
		if ( $num = $this->select_col( '	SELECT count(*)
							  FROM predefmoneyflows' ) ) {
			return $num;
		} else {
			return;
		}
	}

	function get_all_data() {
		return $this->select_rows( "	SELECT predefmoneyflowid
						      ,calc_amount(amount,'OUT',mur_userid,createdate) amount
						      ,mcs_capitalsourceid
						      ,mcp_contractpartnerid
						      ,comment
						  FROM predefmoneyflows
						 WHERE mur_userid = ".USERID."
						 ORDER BY predefmoneyflowid" );
	}

	function get_valid_data( $date='' ) {
		$date = $this->make_date($date);
		return $this->select_rows( "	SELECT mpm.predefmoneyflowid
						      ,calc_amount(mpm.amount,'OUT',mpm.mur_userid,mpm.createdate) amount
						      ,mpm.mcs_capitalsourceid
						      ,mpm.mcp_contractpartnerid
						      ,mpm.comment
						  FROM predefmoneyflows mpm
						      ,capitalsources   mcs
						      ,contractpartners mcp
						 WHERE mpm.mcs_capitalsourceid   = mcs.capitalsourceid
						   AND $date                       BETWEEN mcs.validfrom AND mcs.validtil
						   AND mpm.mcp_contractpartnerid = mcp.contractpartnerid 
						   AND mpm.mur_userid            = ".USERID."
						   AND mcs.mur_userid            = mpm.mur_userid
						   AND mcp.mur_userid            = mpm.mur_userid
						 ORDER BY predefmoneyflowid" );
	}

	function get_id_data( $id ) {
		return $this->select_row( "	SELECT predefmoneyflowid
						      ,calc_amount(amount,'OUT',mur_userid,createdate) amount
						      ,mcs_capitalsourceid
						      ,mcp_contractpartnerid
						      ,comment
						  FROM predefmoneyflows
						 WHERE predefmoneyflowid = $id
						   AND mur_userid        = ".USERID );
	}

	function get_capitalsourceid( $id ) {
		return $this->select_col( "	SELECT capitalsourceid
						  FROM predefmoneyflows
						 WHERE predefmoneyflowid = $id
						   AND mur_userid        = ".USERID );
	}

	function get_all_index_letters() {
		$coreContractPartners=new coreContractPartners();
		$temp=$this->select_cols( '	SELECT DISTINCT mcp_contractpartnerid
						  FROM predefmoneyflows
						 WHERE mur_userid = '.USERID );
		return $coreContractPartners->get_ids_index_letters( $temp );
	}

	function get_all_matched_data( $letter ) {
		$coreContractPartners=new coreContractPartners();
		$ids=$coreContractPartners->get_ids_matched_data( $letter );
		if( is_array( $ids ) ) {
			$idstring=implode( $ids, ',' );
			return $this->select_rows( "	SELECT predefmoneyflowid
							      ,calc_amount(amount,'OUT',mur_userid,createdate) amount
							      ,capitalsourceid
							      ,mcp_contractpartnerid
							      ,comment
							  FROM predefmoneyflows
							 WHERE mcp_contractpartnerid IN ($idstring)
							   AND mur_userid            = ".USERID."
							 ORDER BY comment" );
		} else {
			return;
		}
	}


	function delete_predefmoneyflow( $id ) {
		return $this->delete_row( "	DELETE FROM predefmoneyflows
						 WHERE predefmoneyflowid = $id
						   AND mur_userid        = ".USERID."
						 LIMIT 1" );
	}


	function update_predefmoneyflow( $id, $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		if( fix_amount( $amount ) ) {
			return $this->update_row( "	UPDATE predefmoneyflows
							   SET amount                = calc_amount('$amount','IN',".USERID.",createdate)
							      ,mcs_capitalsourceid   = '$capitalsourceid'
							      ,mcp_contractpartnerid = '$contractpartnerid'
							      ,comment               = '$comment'
							 WHERE predefmoneyflowid = $id
							   AND mur_userid        = ".USERID );
		} else {
			return false;
		}
	}

	function add_predefmoneyflow( $amount, $capitalsourceid, $contractpartnerid, $comment ) {
		if( fix_amount( $amount ) ) {
			return $this->insert_row( "	INSERT INTO predefmoneyflows 
							      (mur_userid
							      ,amount
							      ,mcs_capitalsourceid
							      ,mcp_contractpartnerid
							      ,comment
							      )
							       VALUES
							      (".USERID."
							      ,calc_amount('$amount','IN',".USERID.",NOW())
							      ,'$capitalsourceid'
							      ,'$contractpartnerid'
							      ,'$comment'
							      )" );
		} else {
			return false;
		}
	}
}
