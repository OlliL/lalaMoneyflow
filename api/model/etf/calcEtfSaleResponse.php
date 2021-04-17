<?php

namespace api\model\etf;

use api\model\validation\validationResponse;

class calcEtfSaleResponse extends validationResponse {
	public $isin;
	public $originalBuyPrice;
	public $sellPrice;
	public $newBuyPrice;
	public $profit;
	public $chargeable;
	public $transactionCosts;
	public $rebuyLosses;
	public $overallCosts;
	public $pieces;

	/**
	 *
	 * @return mixed
	 */
	public final function getIsin() {
		return $this->isin;
	}

	/**
	 *
	 * @param mixed $isin
	 */
	public final function setIsin($isin) {
		$this->isin = $isin;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getOriginalBuyPrice() {
		return $this->originalBuyPrice;
	}

	/**
	 *
	 * @param mixed $originalBuyPrice
	 */
	public final function setOriginalBuyPrice($originalBuyPrice) {
		$this->originalBuyPrice = $originalBuyPrice;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getSellPrice() {
		return $this->sellPrice;
	}

	/**
	 *
	 * @param mixed $sellPrice
	 */
	public final function setSellPrice($sellPrice) {
		$this->sellPrice = $sellPrice;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getNewBuyPrice() {
		return $this->newBuyPrice;
	}

	/**
	 *
	 * @param mixed $newBuyPrice
	 */
	public final function setNewBuyPrice($newBuyPrice) {
		$this->newBuyPrice = $newBuyPrice;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getProfit() {
		return $this->profit;
	}

	/**
	 *
	 * @param mixed $profit
	 */
	public final function setProfit($profit) {
		$this->profit = $profit;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getChargeable() {
		return $this->chargeable;
	}

	/**
	 *
	 * @param mixed $chargeable
	 */
	public final function setChargeable($chargeable) {
		$this->chargeable = $chargeable;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getTransactionCosts() {
		return $this->transactionCosts;
	}

	/**
	 *
	 * @param mixed $transactionCosts
	 */
	public final function setTransactionCosts($transactionCosts) {
		$this->transactionCosts = $transactionCosts;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getRebuyLosses() {
		return $this->rebuyLosses;
	}

	/**
	 *
	 * @param mixed $rebuyLosses
	 */
	public final function setRebuyLosses($rebuyLosses) {
		$this->rebuyLosses = $rebuyLosses;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getOverallCosts() {
		return $this->overallCosts;
	}

	/**
	 *
	 * @param mixed $overallCosts
	 */
	public final function setOverallCosts($overallCosts) {
		$this->overallCosts = $overallCosts;
	}

	/**
	 *
	 * @return mixed
	 */
	public final function getPieces() {
		return $this->pieces;
	}

	/**
	 *
	 * @param mixed $pieces
	 */
	public final function setPieces($pieces) {
		$this->pieces = $pieces;
	}
}

