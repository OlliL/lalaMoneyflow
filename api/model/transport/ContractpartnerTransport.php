<?php

namespace rest\api\model\transport;

class ContractpartnerTransport {
	public $id;
	public $userid;
	public $name;
	public $street;
	public $postcode;
	public $town;
	public $country;

	public final function setId($id) {
		$this->id = $id;
	}

	public final function setUserid($userid) {
		$this->userid = $userid;
	}

	public final function setName($name) {
		$this->name = $name;
	}

	public final function setStreet($street) {
		$this->street = $street;
	}

	public final function setPostcode($postcode) {
		$this->postcode = $postcode;
	}

	public final function setTown($town) {
		$this->town = $town;
	}

	public final function setCountry($country) {
		$this->country = $country;
	}

	public final function getId() {
		return $this->id;
	}

	public final function getUserid() {
		return $this->userid;
	}

	public final function getName() {
		return $this->name;
	}

	public final function getStreet() {
		return $this->street;
	}

	public final function getPostcode() {
		return $this->postcode;
	}

	public final function getTown() {
		return $this->town;
	}

	public final function getCountry() {
		return $this->country;
	}
}

?>