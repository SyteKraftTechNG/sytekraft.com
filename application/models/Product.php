<?php
class Product extends Model
{

	private $productGroupId;
	private $name;
	private $description;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getProductGroupId() {
		return $this->productGroupId;
	}

	public function setProductGroupId($productGroupId) {
		$this->productGroupId = $productGroupId;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

}
