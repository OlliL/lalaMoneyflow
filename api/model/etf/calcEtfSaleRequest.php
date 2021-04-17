<?php

namespace api\model\etf;

class calcEtfSaleRequest {
	public $isin;
	public $pieces;
	public $bidPrice;
	public $askPrice;
	public $transactionCosts;
	/**
	 * @return mixed
	 */
	public final function getIsin() {
		return $this->isin;
	}

	/**
	 * @param mixed $isin
	 */
	public final function setIsin($isin) {
		$this->isin = $isin;
	}

	/**
	 * @return mixed
	 */
	public final function getPieces() {
		return $this->pieces;
	}

	/**
	 * @param mixed $pieces
	 */
	public final function setPieces($pieces) {
		$this->pieces = $pieces;
	}

	/**
	 * @return mixed
	 */
	public final function getBidPrice() {
		return $this->bidPrice;
	}

	/**
	 * @param mixed $bidPrice
	 */
	public final function setBidPrice($bidPrice) {
		$this->bidPrice = $bidPrice;
	}

	/**
	 * @return mixed
	 */
	public final function getAskPrice() {
		return $this->askPrice;
	}

	/**
	 * @param mixed $askPrice
	 */
	public final function setAskPrice($askPrice) {
		$this->askPrice = $askPrice;
	}

	/**
	 * @return mixed
	 */
	public final function getTransactionCosts() {
		return $this->transactionCosts;
	}

	/**
	 * @param mixed $transactionCosts
	 */
	public final function setTransactionCosts($transactionCosts) {
		$this->transactionCosts = $transactionCosts;
	}


}

