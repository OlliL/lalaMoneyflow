<?php
//
// Copyright (c) 2005-2014 Oliver Lehmann <oliver@laladev.org>
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
// $Id: core.php,v 1.30 2014/02/23 13:15:54 olivleh1 Exp $
//
require_once 'DbConnection.php';

class core {
	private $db;

	public function __construct() {
	}

	function query($query) {
		GLOBAL $money_debug;

		if ($this->db == null)
			$this->db = DbConnection::getInstance()->getConnection();

		$result = $this->db->query( $query );
		return $result;
	}

	function select_col($query) {
		$reslink = $this->query( $query );
		if ($reslink->errorCode() != 0)
			die( $reslink->errorInfo() );
		if ($reslink->rowCount() <= 1) {
			list ( $retval ) = $reslink->fetch( \PDO::FETCH_NUM );
			return $retval;
		} else {
			die( 'more than one results, but it should be unique!' );
		}
	}

	function generic_query($query) {
		$reslink = $this->query( $query );
		if ($reslink->errorCode() != 0)
			die( $reslink->errorInfo() );
		return true;
	}

	function insert_row($query) {
		$this->generic_query( $query );
		return $this->select_col( 'SELECT LAST_INSERT_ID()' );
	}

	function update_row($query) {
		return $this->generic_query( $query );
	}

}
