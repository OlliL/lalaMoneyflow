<?php

//
// Copyright (c) 2005-2019 Oliver Lehmann <lehmann@ans-netz.de>
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
// 1. Redistributions of source code must retain the above copyright
// notice, this list of conditions and the following disclaimer
// 2. Redistributions in binary form must reproduce the above copyright
// notice, this list of conditions and the following disclaimer in the
// documentation and/or other materials provided with the distribution.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
// ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
// ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
// FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
// DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
// OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
// LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
// OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
// SUCH DAMAGE.
//
// $Id: module.php,v 1.89 2016/09/17 22:47:45 olivleh1 Exp $
//
namespace client\module;

use client\core\coreText;
use client\util\Environment;
use base\ErrorCode;
use client\util\ErrorHandler;
use client\util\DateUtil;
use base\Configuration;
use client\handler\PostingAccountControllerHandler;

require_once 'Smarty.class.php';
abstract class module {
	protected $template;

	protected function template_assign_raw($name, $value) {
		$this->template->assign( $name, $value );
	}

	protected function template_assign($name, $value) {
		if ($name === 'ERRORS') {
			$value_escaped = $value;
		} else if (is_array( $value )) {
			array_walk_recursive( $value, function (&$func_value) {
				$func_value = htmlentities( $func_value );
			} );
			$value_escaped = $value;
		} else {
			$value_escaped = htmlentities( $value );
		}
		$this->template->assign( $name, $value_escaped );
	}

	protected function __construct() {
		$this->template = new \Smarty();
		$this->template->error_unassigned = false;
		$this->template->error_reporting = E_ERROR;
		$this->template->registerPlugin( 'modifier', 'number_format', 'client\util\SmartyPlugin::my_number_format' );
		$this->template->registerPlugin( 'modifier', 'number_format_variable', 'client\util\SmartyPlugin::my_number_format_variable' );
		$this->template_assign( 'ENV_INDEX_PHP', 'index.php' );
		// $this->template->setCompileCheck( \Smarty::COMPILECHECK_OFF );

		if (! empty( $_SERVER ['HTTP_REFERER'] )) {
			$http_referer = $_SERVER ['HTTP_REFERER'];
		} else {
			$http_referer = '';
		}

		if (! empty( $_POST ['REFERER'] )) {
			$referer = $_POST ['REFERER'];
		} elseif (! empty( $_GET ['REFERER'] )) {
			$referer = $_GET ['REFERER'];
		} else {
			$referer = '';
		}

		if ((! empty( $_POST ['sr'] ) && $_POST ['sr'] == 1) || (! empty( $_GET ['sr'] ) && $_GET ['sr'] == 1)) {
			$this->template_assign( 'ENV_REFERER', $http_referer );
		} else {
			// Check for XSS
			if (parse_url( $referer ) ['path'] != $_SERVER ['SCRIPT_NAME']) {
				$referer = $_SERVER ['SCRIPT_NAME'];
			}
			$this->template_assign( 'ENV_REFERER', $referer );
		}
	}

	protected final function get_errors() {
		$result = array ();
		$errors = ErrorHandler::getErrors();
		if (is_array( $errors )) {
			$coreText = new coreText();
			foreach ( $errors as $error ) {
				$error_text = $coreText->get_text( $error ['id'] );
				if (array_key_exists( 'arguments', $error ) && is_array( $error ['arguments'] )) {
					foreach ( $error ['arguments'] as $id => $value ) {
						$error_text = str_replace( 'A' . ($id + 1) . 'A', $value, $error_text );
					}
				}
				$result [] = $error_text;
			}
		}
		return $result;
	}

	private final function loadLanguageFile() {
		$this->template->configLoad( 'locale/' . Environment::getInstance()->getSettingGuiLanguage() . '.conf' );
	}

	protected final function fetch_template($name, $cacheid = false) {
		if (! $cacheid) {
			$this->loadLanguageFile();
			$result = $this->template->fetch( './' . $name );
		} else {
			if (! $this->template->isCached( './' . $name, $cacheid ))
				$this->loadLanguageFile();
			$result = $this->template->fetch( './' . $name, $cacheid );
		}
		return $result;
	}

	private final function addEmbeddedForms() {
		$coreText = new coreText();
		$embeddedForms = array ();

		$this->template_assign_raw( 'IS_EMBEDDED', true );
		$this->template_assign_raw( 'JSON_FORM_DEFAULTS', "[]" );
		$this->template_assign_raw( "HEADER", "" );
		$this->template_assign_raw( "FOOTER", "" );

		// Capitalsource
		$type_values = $coreText->get_domain_data( 'CAPITALSOURCE_TYPE' );
		$state_values = $coreText->get_domain_data( 'CAPITALSOURCE_STATE' );
		$this->template_assign( 'TYPE_VALUES', $type_values );
		$this->template_assign( 'STATE_VALUES', $state_values );

		$embeddedEditCapitalsource = $this->fetch_template( 'display_edit_capitalsource_bs.tpl' );
		$embeddedForms ["EMBEDDED_ADD_CAPITALSOURCE"] = $embeddedEditCapitalsource;

		// Contractpartner
		$listPostingAccounts = PostingAccountControllerHandler::getInstance()->showPostingAccountList( 'all' );
		$postingAccounts = $listPostingAccounts ['postingAccounts'];
		$this->template_assign( 'HEAD_POSTINGACCOUNT_VALUES', $postingAccounts );

		$embeddedEditContractpartner = $this->fetch_template( 'display_edit_contractpartner_bs.tpl' );
		$embeddedForms ["EMBEDDED_ADD_CONTRACTPARTNER"] = $embeddedEditContractpartner;

		// PostingAccount
		$admin = Environment::getInstance()->getUserPermAdmin();
		if ($admin) {
			$embeddedPostingAccount = $this->fetch_template( 'display_edit_postingaccount_bs.tpl' );
			$embeddedForms ["EMBEDDED_ADD_POSTINGACCOUNT"] = $embeddedPostingAccount;
		}

		return $embeddedForms;
	}

	protected final function parse_header_without_embedded($nonavi, $template) {
		$this->parse_header_internal( $nonavi, true, $template, false );
	}

	protected final function parse_header($nonavi = 0) {
		$this->parse_header_internal( $nonavi, false, null, true );
	}

	protected final function parse_header_bootstraped($nonavi, $template) {
		$this->parse_header_internal( $nonavi, true, $template, true );
	}

	private final function parse_header_internal($nonavi, $isBootstrapped, $template, $addEmbeddedForms) {
		$this->template->assign( 'REPORTS_YEAR', date( 'Y' ) );
		$this->template->assign( 'REPORTS_MONTH', date( 'm' ) );
		$this->template->assign( 'VERSION', '0.23.0' );
		$this->template_assign( 'NO_NAVIGATION', $nonavi );
		$this->template_assign( 'TEMPLATE', $template );
		$this->template_assign( 'MAX_DATE', Configuration::getInstance()->getProperty( 'max_year' ) );
		$this->template_assign_raw( 'TODAY', $this->convertDateToGui( date( 'Y-m-d' ) ) );

		$admin = Environment::getInstance()->getUserPermAdmin();
		if ($admin) {
			$this->template->assign( 'IS_ADMIN', true );
		} else {
			$this->template->assign( 'IS_ADMIN', false );
		}
		$cache_id = Environment::getInstance()->getUserId();
		$language = Environment::getInstance()->getSettingGuiLanguage();
		// deactivated for highlighting current screen in menu
		// $this->template->setCaching( true );

		if ($isBootstrapped) {
			$file_header = 'display_header_bs.tpl';
			$file_footer = 'display_footer_bs.tpl';
		} else {
			$file_header = 'display_header.tpl';
			$file_footer = 'display_footer.tpl';
		}

		if ($addEmbeddedForms) {
			$embeddedForms = $this->addEmbeddedForms();
			if (count( $embeddedForms ) > 0) {
				foreach ( $embeddedForms as $key => $value ) {
					$this->template->assign( $key, $value );
				}
			}
		}

		$this->template_assign_raw( 'IS_EMBEDDED', false );
		$header = $this->fetch_template( $file_header, false );
		$this->template->assign( 'HEADER', $header );

		$footer = $this->fetch_template( $file_footer, 'footer_' . $language . '_' . $cache_id );
		$this->template->assign( 'FOOTER', $footer );
		$this->template->setCaching( false );
	}

	protected final function fix_amount(&$amount) {
		$return = true;

		if (preg_match( '/^-{0,1}[0-9]*([\.][0-9][0-9][0-9]){0,}([,][0-9]{1,2}){0,1}$/', $amount )) {
			$amount = str_replace( '.', '', $amount );
			$amount = str_replace( ',', '.', $amount );
		} elseif (preg_match( '/^-{0,1}[0-9]*([,][0-9][0-9][0-9]){0,}([\.][0-9]{1,2}){0,1}$/', $amount )) {
			$amount = str_replace( ',', '', $amount );
		} else {
			$this->add_error( ErrorCode::AMOUNT_IN_WRONG_FORMAT, array (
					$amount
			) );
			$return = false;
		}

		return $return;
	}

	protected final function add_error($id, $args = null) {
		ErrorHandler::addError( $id, $args );
	}

	protected final function dateIsValid($date) {
		return DateUtil::validateStringDate( $date );
	}

	protected final function convertDateToGui($date) {
		return DateUtil::convertStringDateToClient( $date );
	}

	protected final function sort_contractpartner($contractpartner_values) {
		$transliterator = \Transliterator::createFromRules( ':: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD );
		if (is_array( $contractpartner_values ) && count( $contractpartner_values ) > 0) {
			foreach ( $contractpartner_values as $key => $value ) {
				$sortKey1 [$key] = strtolower( $transliterator->transliterate( $value ['name'] ) );
			}

			array_multisort( $sortKey1, SORT_ASC, $contractpartner_values );
		}
		return $contractpartner_values;
	}

	protected final function handleReturnForAjax($ret) {
		if ($ret === true) {
			header( "HTTP/1.1 204 No Content" );
			return null;
		} elseif (array_key_exists( "errors", $ret )) {
			foreach ( $ret ['errors'] as $validationResult ) {
				$this->add_error( $validationResult ['error'] );
			}
			header( 'HTTP/1.1 500 Internal Server Error' );
			return json_encode( $this->get_errors() );
		} else {
			return json_encode( $ret );
		}
	}

	protected final function json_encode_with_null_to_empty_string(array $data) {
		array_walk_recursive( $data, function (&$item, $key) {
			$item = null === $item ? '' : $item;
		} );

		return json_encode( $data );
	}
}
?>
