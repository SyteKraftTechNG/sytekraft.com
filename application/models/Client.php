<?php
class Client extends Model
{

	private $name;
	private $brandName;
	private $officeAddress;
	private $cityId;
	private $phone1;
	private $phone2;
	private $phone3;
	private $email;
	private $accessPersonId;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getBrandName() {
		return $this->brandName;
	}

	public function setBrandName($brandName) {
		$this->brandName = $brandName;
	}

	public function getOfficeAddress() {
		return $this->officeAddress;
	}

	public function setOfficeAddress($officeAddress) {
		$this->officeAddress = $officeAddress;
	}

	public function getCityId() {
		return $this->cityId;
	}

	public function setCityId($cityId) {
		$this->cityId = $cityId;
	}

	public function getPhone1() {
		return $this->phone1;
	}

	public function setPhone1($phone1) {
		$this->phone1 = $phone1;
	}

	public function getPhone2() {
		return $this->phone2;
	}

	public function setPhone2($phone2) {
		$this->phone2 = $phone2;
	}

	public function getPhone3() {
		return $this->phone3;
	}

	public function setPhone3($phone3) {
		$this->phone3 = $phone3;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getAccessPersonId() {
		return $this->accessPersonId;
	}

	public function setAccessPersonId($accessPersonId) {
		$this->accessPersonId = $accessPersonId;
	}

}
