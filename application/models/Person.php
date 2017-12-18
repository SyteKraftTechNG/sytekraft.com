<?php
class Person extends Model
{

	private $domain;
	private $fullName;
	private $shortName;
	private $briefBio;
	private $briefAboutCompany;
	private $aviSrc;
	private $headerSrc;
	private $backgroundSrc;
	private $roleId;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getDomain() {
		return $this->domain;
	}

	public function setDomain($domain) {
		$this->domain = $domain;
	}

	public function getFullName() {
		return $this->fullName;
	}

	public function setFullName($fullName) {
		$this->fullName = $fullName;
	}

	public function getShortName() {
		return $this->shortName;
	}

	public function setShortName($shortName) {
		$this->shortName = $shortName;
	}

	public function getBriefBio() {
		return $this->briefBio;
	}

	public function setBriefBio($briefBio) {
		$this->briefBio = $briefBio;
	}

	public function getBriefAboutCompany() {
		return $this->briefAboutCompany;
	}

	public function setBriefAboutCompany($briefAboutCompany) {
		$this->briefAboutCompany = $briefAboutCompany;
	}

	public function getAviSrc() {
		return $this->aviSrc;
	}

	public function setAviSrc($aviSrc) {
		$this->aviSrc = $aviSrc;
	}

	public function getHeaderSrc() {
		return $this->headerSrc;
	}

	public function setHeaderSrc($headerSrc) {
		$this->headerSrc = $headerSrc;
	}

	public function getBackgroundSrc() {
		return $this->backgroundSrc;
	}

	public function setBackgroundSrc($backgroundSrc) {
		$this->backgroundSrc = $backgroundSrc;
	}

	public function getRoleId() {
		return $this->roleId;
	}

	public function setRoleId($roleId) {
		$this->roleId = $roleId;
	}

}
