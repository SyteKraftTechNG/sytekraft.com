<?php
class ProjectDocument extends Model
{

	private $projectId;
	private $title;
	private $brief;
	private $src;
	private $dateAdded;
	private $addedByUserId;

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

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getBrief() {
		return $this->brief;
	}

	public function setBrief($brief) {
		$this->brief = $brief;
	}

	public function getSrc() {
		return $this->src;
	}

	public function setSrc($src) {
		$this->src = $src;
	}

	public function getDateAdded() {
		return $this->dateAdded;
	}

	public function setDateAdded($dateAdded) {
		$this->dateAdded = $dateAdded;
	}

	public function getAddedByUserId() {
		return $this->addedByUserId;
	}

	public function setAddedByUserId($addedByUserId) {
		$this->addedByUserId = $addedByUserId;
	}

}
