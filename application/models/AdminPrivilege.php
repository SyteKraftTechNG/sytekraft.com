<?php
class AdminPrivilege extends Model
{

	private $userId;
	private $userLevel;
	private $dateGiven;
	private $dateEnded;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getUserId() {
		return $this->userId;
	}

	public function setUserId($userId) {
		$this->userId = $userId;
	}

	public function getUserLevel() {
		return $this->userLevel;
	}

	public function setUserLevel($userLevel) {
		$this->userLevel = $userLevel;
	}

	public function getDateGiven() {
		return $this->dateGiven;
	}

	public function setDateGiven($dateGiven) {
		$this->dateGiven = $dateGiven;
	}

	public function getDateEnded() {
		return $this->dateEnded;
	}

	public function setDateEnded($dateEnded) {
		$this->dateEnded = $dateEnded;
	}

}
