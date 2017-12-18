<?php
class ProjectTeam extends Model
{

	private $projectId;
	private $leaderUserId;
	private $codeName;
	private $dateFormed;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getProjectId() {
		return $this->projectId;
	}

	public function setProjectId($projectId) {
		$this->projectId = $projectId;
	}

	public function getLeaderUserId() {
		return $this->leaderUserId;
	}

	public function setLeaderUserId($leaderUserId) {
		$this->leaderUserId = $leaderUserId;
	}

	public function getCodeName() {
		return $this->codeName;
	}

	public function setCodeName($codeName) {
		$this->codeName = $codeName;
	}

	public function getDateFormed() {
		return $this->dateFormed;
	}

	public function setDateFormed($dateFormed) {
		$this->dateFormed = $dateFormed;
	}

}
