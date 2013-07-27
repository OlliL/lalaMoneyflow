<?php
#-
# Copyright (c) 2007-2013 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: coreCompare.php,v 1.4 2013/07/27 23:06:48 olivleh1 Exp $
#

require_once 'core/core.php';
require_once 'model/CompareDataFormats.php';

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
		$db_array = $this->select_row( "	SELECT formatid
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
		
		$compareDataFormats = new CompareDataFormats();
		$compareDataFormats->setFormatId($db_array['formatid']);
		$compareDataFormats->setName($db_array['name']);
		$compareDataFormats->setStartline($db_array['startline']);
		$compareDataFormats->setDelimiter($db_array['delimiter']);
		$compareDataFormats->setPositionDate($db_array['pos_date']);
		$compareDataFormats->setPositionPartner($db_array['pos_partner']);
		$compareDataFormats->setPositionAmount($db_array['pos_amount']);
		$compareDataFormats->setPositionComment($db_array['pos_comment']);
		$compareDataFormats->setFormatDate($db_array['fmt_date']);
		$compareDataFormats->setFormatAmountThousand($db_array['fmt_amount_thousand']);
		$compareDataFormats->setFormatAmountDecimal($db_array['fmt_amount_decimal']);
		$compareDataFormats->setPositionPartnerAlternative($db_array['pos_partner_alt']);
		$compareDataFormats->setPositionPartnerAlternativePositionKey($db_array['pos_partner_alt_pos_key']);
		$compareDataFormats->setPositionPartnerAlternativeKeyword($db_array['pos_partner_alt_keyword']);
		
		return $compareDataFormats;
	}
}
