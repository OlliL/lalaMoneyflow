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
# $Id: coreContractPartners.php,v 1.8 2006/12/19 14:37:17 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreMoneyFlows.php';

class coreContractPartners extends core {

	function coreContractPartners() {
		$this->core();
	}

	function get_all_data() {
		return $this->select_rows( 'SELECT id,name,street,postcode,town,country FROM contractpartners WHERE userid='.USERID.' ORDER BY name' );
	}

	function get_id_data( $id ) {
		return $this->select_row( "SELECT id,name,street,postcode,town,country FROM contractpartners WHERE id=$id AND userid=".USERID." LIMIT 1" );
	}

	function get_all_index_letters() {
		return $this->select_cols( 'SELECT DISTINCT UPPER(SUBSTR(name,1,1)) letters FROM contractpartners WHERE userid='.USERID.' ORDER BY letters' );
	}

	function get_ids_index_letters( $ids ) {
		if( is_array( $ids ) ) {
			$idstring=implode( $ids, ',' );
			return $this->select_cols( "SELECT DISTINCT UPPER(SUBSTR(name,1,1)) letters FROM contractpartners WHERE id IN ($idstring) AND userid=".USERID." ORDER BY letters" );
		} else {
			return;
		}
	}

	function get_all_matched_data( $letter ) {
		return $this->select_rows( "SELECT id,name,street,postcode,town,country FROM contractpartners WHERE UPPER(name) LIKE UPPER('$letter%') AND userid=".USERID." ORDER BY name" );
	}

	function get_ids_matched_data( $letter ) {
		return $this->select_cols( "SELECT id FROM contractpartners WHERE UPPER(name) LIKE UPPER('$letter%') AND userid=".USERID." ORDER BY name" );
	}

	function get_all_names() {
		$result=$this->select_rows( 'SELECT id,name FROM contractpartners WHERE userid='.USERID.' ORDER BY name' );
		if( is_array( $result ) ) {
			return $result;
		} else {
			add_error( 'no contract partners defined' );
			return;
		}
	}

	function get_name( $id ) {
		return $this->select_col( "SELECT name FROM contractpartners WHERE ID=$id AND userid=".USERID." LIMIT 1" );
	}


	function delete_contractpartner( $id ) {
		$coreMoneyFlows=new coreMoneyFlows();
		if( $coreMoneyFlows->contractpartner_in_use( $id ) ) {
			add_error( "You can't delete a contract partner who is still in use!" );
			return 0;
		} else {
			return $this->delete_row( "DELETE FROM contractpartners WHERE id=$id AND userid=".USERID." LIMIT 1" );
		}
		exit;
	}


	function update_contractpartner( $id, $name, $street, $postcode, $town, $country ) {
		return $this->update_row( "UPDATE contractpartners set name='$name',street='$street',postcode='$postcode',town='$town',country='$country' WHERE id=$id AND userid=".USERID." LIMIT 1" );
	}

	function add_contractpartner( $name, $street, $postcode, $town, $country ) {
		return $this->insert_row( "INSERT INTO contractpartners (userid,name,street,postcode,town,country) VALUES (".USERID.",'$name','$street','$postcode','$town','$country')" );
	}
}
