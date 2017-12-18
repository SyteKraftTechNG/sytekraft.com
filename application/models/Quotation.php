<?php
class Quotation extends Model
{

	private $created;
	private $quoteRequestId;
	private $documentSrc;
	private $sent;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getCreated() {
		return $this->created;
	}

	public function setCreated($created) {
		$this->created = $created;
	}

	public function getQuoteRequestId() {
		return $this->quoteRequestId;
	}

	public function setQuoteRequestId($quoteRequestId) {
		$this->quoteRequestId = $quoteRequestId;
	}

	public function getDocumentSrc() {
		return $this->documentSrc;
	}

	public function setDocumentSrc($documentSrc) {
		$this->documentSrc = $documentSrc;
	}

	public function getSent() {
		return $this->sent;
	}

	public function setSent($sent) {
		$this->sent = $sent;
	}

}
