<?php
#-
# Copyright (c) 2009-2013 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: moduleEvents.php,v 1.4 2013/07/27 23:06:48 olivleh1 Exp $
#

require_once 'module/module.php';
require_once 'core/coreMonthlySettlement.php';
require_once 'core/coreSession.php';

class moduleEvents extends module {

	function moduleEvents() {
		$this->module();
		$this->coreMonthlySettlement = new coreMonthlySettlement();
		$this->coreSession           = new coreSession();
	}

	function check_events() {

		if( $this->coreSession->getAttribute( 'events_shown' ) === false ) {
			$this->coreSession->setAttribute( 'events_shown', true );

		# check if for the previous month, a monthly settlement was done
		# if not, remind the user to do so

		$previous_month = mktime( 0, 0, 0, date( 'm' ) - 1, 1, date( 'Y' ) );
		$month = date( 'm', $previous_month );
		$year  = date( 'Y', $previous_month );

		if( $this->coreMonthlySettlement->monthlysettlement_exists( $month, $year ) === false ) {
			$this->template->assign( 'MONTH', $month  );
			$this->template->assign( 'YEAR',  $year   );

			$this->parse_header(1);
			return $this->fetch_template( 'display_event_monthlysettlement.tpl' );
		}
		}
	}
}

