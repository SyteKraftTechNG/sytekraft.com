<?php
class Service extends Model
{

	private $serviceGroupId;
	private $name;
	private $overview;
	private $active;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getServiceGroupId() {
		return $this->serviceGroupId;
	}

	public function setServiceGroupId($serviceGroupId) {
		$this->serviceGroupId = $serviceGroupId;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getOverview() {
		return $this->overview;
	}

	public function setOverview($overview) {
		$this->overview = $overview;
	}

	public function getActive() {
		return $this->active;
	}

	public function setActive($active) {
		$this->active = $active;
	}

}
