<?php
class QuoteRequest extends Model
{

	private $sent;
	private $ref;
	private $requestor;
	private $email;
	private $phone1;
	private $phone2;
	private $brief;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getSent() {
		return $this->sent;
	}

	public function setSent($sent) {
		$this->sent = $sent;
	}

	public function getRef() {
		return $this->ref;
	}

	public function setRef($ref) {
		$this->ref = $ref;
	}

	public function getRequestor() {
		return $this->requestor;
	}

	public function setRequestor($requestor) {
		$this->requestor = $requestor;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
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

	public function getBrief() {
		return $this->brief;
	}

	public function setBrief($brief) {
		$this->brief = $brief;
	}

}
