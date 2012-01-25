<?php
#-
# Copyright (c) 2005-2012 Oliver Lehmann <oliver@FreeBSD.org>
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
# $Id: module.php,v 1.46 2012/01/25 19:39:49 olivleh1 Exp $
#

require_once 'Smarty.class.php';
require_once 'core/coreTemplates.php';
require_once 'core/coreText.php';
require_once 'core/coreUsers.php';

class module {
	function module() {
		$this->coreTemplates = new coreTemplates;
		$this->coreText  = new coreText;
		$this->coreUsers = new coreUsers;
		$this->template  = new Smarty;
		$this->index_php = 'index.php';
		$this->template->register_modifier( 'number_format', 'my_number_format' );
		$this->template->assign( 'ENV_INDEX_PHP', $this->index_php );
		
		if( !empty( $_SERVER['HTTP_REFERER'] ) ) {
			$http_referer = $_SERVER['HTTP_REFERER'];
		} else {
			$http_referer = '';
		}

		if( !empty( $_POST['REFERER'] ) ) {
			$referer = $_POST['REFERER'];
		} elseif( !empty( $_GET['REFERER'] ) ) {
			$referer = $_GET['REFERER'];
		} else {
			$referer ='';
		}

		if ( ( !empty( $_POST['sr'] ) && $_POST['sr'] == 1 ) || ( !empty( $_GET['sr'] ) && $_GET['sr'] == 1 ) ) {
			$this->template->assign( 'ENV_REFERER', $http_referer );
		} else {
			$this->template->assign( 'ENV_REFERER', $referer );
		}
	}
	
	function get_errors() {
		global $ERRORS;
		if( is_array( $ERRORS ) ) {
			foreach( $ERRORS as $error ) {
				$error_text = $this->coreText->get_error( $error['id'] );
				if( is_array( $error['arguments'] ) ) {
					foreach( $error['arguments'] as $id => $value ) {
						$error_text = str_replace( 'A'.( $id+1 ).'A', $value, $error_text );
					}
				}
				$result[] = $error_text;
			}
		}
		return $result;
	}
	
	function fetch_template( $name ) {
		$text = $this->coreTemplates->get_template_text( $name );
		if( is_array( $text ) ) {
			foreach( $text as $id => $value ) {
				$this->template->assign( $value['variable'], htmlentities( $value['text'] ) );
			}
		}
		$result = $this->template->fetch( './'.$name );
		if( is_array( $text ) ) {
			foreach( $text as $id => $value ) {
				$this->template->clear_assign( $value['variable'] );
			}
		}
		return $result;
	}

	function parse_header( $nonavi = 0 ) {
		$this->template->assign( 'REPORTS_YEAR',   date( 'Y' ) );
		$this->template->assign( 'REPORTS_MONTH',  date( 'm' ) );
		$this->template->assign( 'ENABLE_JPGRAPH', ENABLE_JPGRAPH );
		$this->template->assign( 'VERSION',        '0.11.3' );
		$this->template->assign( 'NO_NAVIGATION',  $nonavi );
		$this->template->assign( 'IS_ADMIN',       $this->coreUsers->check_admin_permission( USERID ) );

		$header = $this->fetch_template( 'display_header.tpl' );
		$this->template->assign( 'HEADER', $header );

		$footer = $this->fetch_template( 'display_footer.tpl' );
		$this->template->assign( 'FOOTER', $footer );
	}	
}
?>
