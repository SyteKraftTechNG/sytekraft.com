<?php
class ProductComponent extends Model
{

	private $productId;
	private $isSingleton;
	private $name;
	private $description;
	private $unit;
	private $minDefaultAmount;
	private $baseCostNaira;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getProductId() {
		return $this->productId;
	}

	public function setProductId($productId) {
		$this->productId = $productId;
	}

	public function getIsSingleton() {
		return $this->isSingleton;
	}

	public function setIsSingleton($isSingleton) {
		$this->isSingleton = $isSingleton;
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

	public function getUnit() {
		return $this->unit;
	}

	public function setUnit($unit) {
		$this->unit = $unit;
	}

	public function getMinDefaultAmount() {
		return $this->minDefaultAmount;
	}

	public function setMinDefaultAmount($minDefaultAmount) {
		$this->minDefaultAmount = $minDefaultAmount;
	}

	public function getBaseCostNaira() {
		return $this->baseCostNaira;
	}

	public function setBaseCostNaira($baseCostNaira) {
		$this->baseCostNaira = $baseCostNaira;
	}

}
