<?php
class Object extends Model
{

	private $schema;
	private $recordId;
	private $searchString;
	private $created;
	private $reference;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getSchema() {
		return $this->schema;
	}

	public function setSchema($schema) {
		$this->schema = $schema;
	}

	public function getRecordId() {
		return $this->recordId;
	}

	public function setRecordId($recordId) {
		$this->recordId = $recordId;
	}

	public function getSearchString() {
		return $this->searchString;
	}

	public function setSearchString($searchString) {
		$this->searchString = $searchString;
	}

	public function getCreated() {
		return $this->created;
	}

	public function setCreated($created = null) {
		if (!isset($created)) $created = NOW;
		$this->created = $created;
	}

	/**
	 * @return mixed
	 */
	public function getReference() {
		return $this->reference;
	}

	/**
	 * @param mixed $reference
	 * @return Object
	 */
	public function setReference($reference = null) {
		if (!isset($reference)) $reference = uniqid(time(), true);
		$this->reference = $reference;
	}
	
}
