<?php
class ProjectMember extends Model
{

	private $userId;
	private $projectTeamId;
	private $dateAdded;
	private $active;

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

	public function getProjectTeamId() {
		return $this->projectTeamId;
	}

	public function setProjectTeamId($projectTeamId) {
		$this->projectTeamId = $projectTeamId;
	}

	public function getDateAdded() {
		return $this->dateAdded;
	}

	public function setDateAdded($dateAdded) {
		$this->dateAdded = $dateAdded;
	}

	public function getActive() {
		return $this->active;
	}

	public function setActive($active) {
		$this->active = $active;
	}

}
