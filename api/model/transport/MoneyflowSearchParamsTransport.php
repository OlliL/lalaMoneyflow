<?php

//
// Copyright (c) 2013-2015 Oliver Lehmann <lehmann@ans-netz.de>
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
// $Id: MoneyflowSearchParamsTransport.php,v 1.5 2015/02/13 00:03:39 olivleh1 Exp $
//
namespace api\model\transport;

class MoneyflowSearchParamsTransport extends AbstractTransport {
	public $startDate;
	public $endDate;
	public $searchString;
	public $featureEqual;
	public $featureRegexp;
	public $featureCaseSensitive;
	public $featureOnlyMinusAmounts;
	public $contractpartnerId;
	public $postingAccountId;
	public $groupBy1;
	public $groupBy2;
	public $orderBy;

	public final function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	public final function setEndDate($endDate) {
		$this->endDate = $endDate;
	}

	public final function setSearchString($searchString) {
		$this->searchString = $searchString;
	}

	public final function setFeatureEqual($featureEqual) {
		$this->featureEqual = $featureEqual;
	}

	public final function setFeatureRegexp($featureRegexp) {
		$this->featureRegexp = $featureRegexp;
	}

	public final function setFeatureCaseSensitive($featureCaseSensitive) {
		$this->featureCaseSensitive = $featureCaseSensitive;
	}

	public final function setFeatureOnlyMinusAmounts($featureOnlyMinusAmounts) {
		$this->featureOnlyMinusAmounts = $featureOnlyMinusAmounts;
	}

	public final function setContractpartnerId($contractpartnerId) {
		$this->contractpartnerId = $contractpartnerId;
	}

	public final function setGroupBy1($groupBy1) {
		$this->groupBy1 = $groupBy1;
	}

	public final function setGroupBy2($groupBy2) {
		$this->groupBy2 = $groupBy2;
	}

	public final function setOrderBy($orderBy) {
		$this->orderBy = $orderBy;
	}

	public final function getStartDate() {
		return $this->startDate;
	}

	public final function getEndDate() {
		return $this->endDate;
	}

	public final function getSearchString() {
		return $this->searchString;
	}

	public final function getFeatureEqual() {
		return $this->featureEqual;
	}

	public final function getFeatureRegexp() {
		return $this->featureRegexp;
	}

	public final function getFeatureCaseSensitive() {
		return $this->featureCaseSensitive;
	}

	public final function getFeatureOnlyMinusAmounts() {
		return $this->featureOnlyMinusAmounts;
	}

	public final function getContractpartnerId() {
		return $this->contractpartnerId;
	}

	public final function getGroupBy1() {
		return $this->groupBy1;
	}

	public final function getGroupBy2() {
		return $this->groupBy2;
	}

	public final function getOrderBy() {
		return $this->orderBy;
	}

	public final function setPostingAccountId($postingAccountId) {
		$this->postingAccountId = $postingAccountId;
	}

	public final function getPostingAccountId() {
		return $this->postingAccountId;
	}
}

?>