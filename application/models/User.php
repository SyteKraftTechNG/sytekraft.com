<?php
class User extends Model
{

	private $personId;
	private $username;
	private $password;
	private $emailSignatureImgSrc;
	private $emailCloser;
	private $active;
	private $dateActivated;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getPersonId() {
		return $this->personId;
	}

	public function setPersonId($personId) {
		$this->personId = $personId;
	}

	public function getUsername() {
		return $this->username;
	}

	public function setUsername($username) {
		$this->username = $username;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function getEmailSignatureImgSrc() {
		return $this->emailSignatureImgSrc;
	}

	public function setEmailSignatureImgSrc($emailSignatureImgSrc) {
		$this->emailSignatureImgSrc = $emailSignatureImgSrc;
	}

	public function getEmailCloser() {
		return $this->emailCloser;
	}

	public function setEmailCloser($emailCloser) {
		$this->emailCloser = $emailCloser;
	}

	public function getActive() {
		return $this->active;
	}

	public function setActive($active) {
		$this->active = $active;
	}

	public function getDateActivated() {
		return $this->dateActivated;
	}

	public function setDateActivated($dateActivated) {
		$this->dateActivated = $dateActivated;
	}

}
