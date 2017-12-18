<?php
class Project extends Model
{

	private $name;
	private $brief;
	private $clientId;
	private $startDueDate;
	private $endDueDate;
	private $quotationId;
	private $managerUserId;
	private $leaderUserId;

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

	public function getBrief() {
		return $this->brief;
	}

	public function setBrief($brief) {
		$this->brief = $brief;
	}

	public function getClientId() {
		return $this->clientId;
	}

	public function setClientId($clientId) {
		$this->clientId = $clientId;
	}

	public function getStartDueDate() {
		return $this->startDueDate;
	}

	public function setStartDueDate($startDueDate) {
		$this->startDueDate = $startDueDate;
	}

	public function getEndDueDate() {
		return $this->endDueDate;
	}

	public function setEndDueDate($endDueDate) {
		$this->endDueDate = $endDueDate;
	}

	public function getQuotationId() {
		return $this->quotationId;
	}

	public function setQuotationId($quotationId) {
		$this->quotationId = $quotationId;
	}

	public function getManagerUserId() {
		return $this->managerUserId;
	}

	public function setManagerUserId($managerUserId) {
		$this->managerUserId = $managerUserId;
	}

	public function getLeaderUserId() {
		return $this->leaderUserId;
	}

	public function setLeaderUserId($leaderUserId) {
		$this->leaderUserId = $leaderUserId;
	}

}
