<?php
class ServiceProductGroup extends Model
{

	private $serviceId;
	private $productGroupId;
	private $active;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getServiceId() {
		return $this->serviceId;
	}

	public function setServiceId($serviceId) {
		$this->serviceId = $serviceId;
	}

	public function getProductGroupId() {
		return $this->productGroupId;
	}

	public function setProductGroupId($productGroupId) {
		$this->productGroupId = $productGroupId;
	}

	public function getActive() {
		return $this->active;
	}

	public function setActive($active) {
		$this->active = $active;
	}

}
