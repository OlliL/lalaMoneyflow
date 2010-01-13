<?php
#-
# Copyright (c) 2007-2010 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreCompare.php,v 1.2 2010/01/13 10:15:43 olivleh1 Exp $
#

require_once 'core/core.php';

class coreCompare extends core {

	function coreCompare() {
		$this->core();
	}

	function get_all_data() {
		return $this->select_rows( '	SELECT formatid
						      ,name
						  FROM cmp_data_formats' );
	}

	function get_id_data( $id ) {
		return $this->select_row( "	SELECT formatid
						      ,name
						      ,startline
						      ,delimiter
						      ,pos_date
						      ,pos_partner
						      ,pos_amount
						      ,pos_comment
						      ,fmt_date
						      ,fmt_amount_thousand
						      ,fmt_amount_decimal
						      ,pos_partner_alt
						      ,pos_partner_alt_pos_key
						      ,pos_partner_alt_keyword
						  FROM cmp_data_formats
						 WHERE formatid = $id
						 LIMIT 1" );
	}
}
