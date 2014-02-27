<?php
//
// Copyright (c) 2013-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: SettingControllerHandler.php,v 1.2 2014/02/27 19:31:01 olivleh1 Exp $
//
namespace rest\client\handler;

use rest\base\JsonAutoMapper;
use rest\api\model\setting\updateDefaultSettingsRequest;
use rest\api\model\setting\updatePersonalSettingsRequest;

class SettingControllerHandler extends AbstractHandler {
	private static $instance;

	protected function __construct() {
		parent::__construct();
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			self::$instance = new SettingControllerHandler();
		}
		return self::$instance;
	}

	public final function showDefaultSettings() {
		$url = URLPREFIX . SERVERPREFIX . 'setting/showDefaultSettings/' . parent::getSessionId();
		$response = parent::getJson( $url );
		if (is_array( $response )) {
			$showDefaultSettingsResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\setting' );
			$result ['maxrows'] = $showDefaultSettingsResponse->getMaxRows();
			$result ['language'] = $showDefaultSettingsResponse->getLanguage();
			$result ['numflows'] = $showDefaultSettingsResponse->getNumFreeMoneyflows();
			$result ['dateformat'] = $showDefaultSettingsResponse->getDateFormat();
		}

		return $result;
	}

	public final function updateDefaultSettings(array $settings) {
		$url = URLPREFIX . SERVERPREFIX . 'setting/updateDefaultSettings/' . parent::getSessionId();

		$request = new updateDefaultSettingsRequest();
		$request->setDateFormat( $settings ['dateformat'] );
		$request->setLanguage( $settings ['language'] );
		$request->setMaxRows( $settings ['maxrows'] );
		$request->setNumFreeMoneyflows( $settings ['numflows'] );

		return parent::putJson( $url, parent::json_encode_response( $request ) );
	}

	public final function showPersonalSettings() {
		$url = URLPREFIX . SERVERPREFIX . 'setting/showPersonalSettings/' . parent::getSessionId();
		$response = parent::getJson( $url );
		if (is_array( $response )) {
			$showPersonalSettingsResponse = JsonAutoMapper::mapAToB( $response, '\\rest\\api\\model\\setting' );
			$result ['maxrows'] = $showPersonalSettingsResponse->getMaxRows();
			$result ['language'] = $showPersonalSettingsResponse->getLanguage();
			$result ['numflows'] = $showPersonalSettingsResponse->getNumFreeMoneyflows();
			$result ['dateformat'] = $showPersonalSettingsResponse->getDateFormat();
		}

		return $result;
	}

	public final function updatePersonalSettings(array $settings) {
		$url = URLPREFIX . SERVERPREFIX . 'setting/updatePersonalSettings/' . parent::getSessionId();

		$request = new updatePersonalSettingsRequest();
		$request->setDateFormat( $settings ['dateformat'] );
		$request->setLanguage( $settings ['language'] );
		$request->setMaxRows( $settings ['maxrows'] );
		$request->setNumFreeMoneyflows( $settings ['numflows'] );
		$request->setPassword( $settings ['password'] );

		return parent::putJson( $url, parent::json_encode_response( $request ) );
	}
}

?>