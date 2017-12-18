<?php
class QuoteComponent extends Model
{

	private $quoteRequestId;
	private $productComponentId;
	private $amount;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getQuoteRequestId() {
		return $this->quoteRequestId;
	}

	public function setQuoteRequestId($quoteRequestId) {
		$this->quoteRequestId = $quoteRequestId;
	}

	public function getProductComponentId() {
		return $this->productComponentId;
	}

	public function setProductComponentId($productComponentId) {
		$this->productComponentId = $productComponentId;
	}

	public function getAmount() {
		return $this->amount;
	}

	public function setAmount($amount) {
		$this->amount = $amount;
	}

}
