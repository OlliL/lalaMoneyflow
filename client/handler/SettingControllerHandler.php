<?php
//
// Copyright (c) 2013-2015 Oliver Lehmann <oliver@laladev.org>
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
// $Id: SettingControllerHandler.php,v 1.10 2015/09/13 17:43:10 olivleh1 Exp $
//
namespace client\handler;

use api\model\setting\updateDefaultSettingsRequest;
use api\model\setting\updatePersonalSettingsRequest;
use api\model\setting\showDefaultSettingsResponse;
use api\model\setting\showPersonalSettingsResponse;
use base\Singleton;

class SettingControllerHandler extends AbstractHandler {
	use Singleton;

	protected function init() {
		parent::init();
	}

	protected final function getCategory() {
		return 'setting';
	}

	public final function showDefaultSettings() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showDefaultSettingsResponse) {
			$result ['maxrows'] = $response->getMaxRows();
			$result ['language'] = $response->getLanguage();
			$result ['dateformat'] = $response->getDateFormat();
		}

		return $result;
	}

	public final function updateDefaultSettings(array $settings) {
		$request = new updateDefaultSettingsRequest();
		$request->setDateFormat( $settings ['dateformat'] );
		$request->setLanguage( $settings ['language'] );
		$request->setMaxRows( $settings ['maxrows'] );

		return parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}

	public final function showPersonalSettings() {
		$response = parent::getJson( __FUNCTION__ );
		$result = null;
		if ($response instanceof showPersonalSettingsResponse) {
			$result ['maxrows'] = $response->getMaxRows();
			$result ['language'] = $response->getLanguage();
			$result ['dateformat'] = $response->getDateFormat();
		}

		return $result;
	}

	public final function updatePersonalSettings(array $settings) {
		$request = new updatePersonalSettingsRequest();
		$request->setDateFormat( $settings ['dateformat'] );
		$request->setLanguage( $settings ['language'] );
		$request->setMaxRows( $settings ['maxrows'] );
		$request->setPassword( $settings ['password'] );

		return parent::putJson( __FUNCTION__, parent::json_encode_response( $request ) );
	}
}

?>