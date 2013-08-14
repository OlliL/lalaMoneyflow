<?php
#-
# Copyright (c) 2005-2013 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreCapitalSources.php,v 1.38 2013/08/14 16:15:24 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'core/coreMoneyFlows.php';
require_once 'core/coreDomains.php';

class coreCapitalSources extends core {

	function coreCapitalSources() {
		parent::__construct();
	}

	function count_all_valid_data( $datefrom='', $datetil='' ) {
		$datefrom = $this->make_date( $datefrom );

		if( empty( $datetil ) )
			$datetil = $datefrom;
		else
			$datetil = $this->make_date( $datetil );

		if ( $num = $this->select_col( "SELECT count(*)
						  FROM vw_capitalsources
						 WHERE validfrom <= $datetil
						   AND validtil  >= $datefrom
						   AND mug_mur_userid = ".USERID ) ) {
			return $num;
		} else {
			return;
		}
	}

	function get_valid_ids( $datefrom='', $datetil='' ) {
		$datefrom = $this->make_date( $datefrom );

		if( empty( $datetil ) )
			$datetil = $datefrom;
		else
			$datetil = $this->make_date( $datetil );

		return $this->select_cols( "	SELECT capitalsourceid
						  FROM capitalsources
						 WHERE validfrom <= $datetil
						   AND validtil  >= $datefrom
						   AND mur_userid = ".USERID."
						 ORDER BY capitalsourceid" );
	}

	function get_all_comments() {
		return $this->select_rows( '	SELECT capitalsourceid
						      ,comment
						  FROM vw_capitalsources
						 WHERE mug_mur_userid = '.USERID.'
						 ORDER BY capitalsourceid' );
	}

	function get_valid_comments( $date='' ) {
		$date = $this->make_date($date);
		$result=$this->select_rows( "	SELECT capitalsourceid
						       ,comment
						   FROM vw_capitalsources
						  WHERE $date	BETWEEN validfrom AND validtil
						    AND mug_mur_userid = ".USERID."
						    AND (mur_userid = ".USERID."
						         OR
						         att_group_use = 1
						        )
						  ORDER BY CASE WHEN mur_userid = ".USERID." THEN 1 ELSE 2 END, capitalsourceid" );
		if( is_array( $result ) ) {
			return $result;
		} else {
			add_error( 119 );
			return;
		}
	}

	function get_comment( $id ) {
		return $this->select_col( "	SELECT comment
						  FROM vw_capitalsources
						 WHERE capitalsourceid = $id
						   AND mug_mur_userid  = ".USERID."
						 LIMIT 1" );
	}

	function id_is_valid( $id, $date='' ) {
		$date = $this->make_date($date);
		return $this->select_col( "	SELECT 1
						  FROM vw_capitalsources
						 WHERE capitalsourceid = $id
						   AND $date	       BETWEEN validfrom AND validtil
						   AND mug_mur_userid  = ".USERID."
						 LIMIT 1" );
	}

}
