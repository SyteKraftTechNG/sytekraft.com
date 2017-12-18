<?php
class Enquiry extends Model
{

	private $sent;
	private $sender;
	private $phone;
	private $email;
	private $topic;
	private $details;
	private $ipAddress;
	private $asRead;

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

	public function getSender() {
		return $this->sender;
	}

	public function setSender($sender) {
		$this->sender = $sender;
	}

	public function getPhone() {
		return $this->phone;
	}

	public function setPhone($phone) {
		$this->phone = $phone;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getTopic() {
		return $this->topic;
	}

	public function setTopic($topic) {
		$this->topic = $topic;
	}

	public function getDetails() {
		return $this->details;
	}

	public function setDetails($details) {
		$this->details = $details;
	}

	public function getIpAddress() {
		return $this->ipAddress;
	}

	public function setIpAddress($ipAddress) {
		$this->ipAddress = $ipAddress;
	}

	public function getAsRead() {
		return $this->asRead;
	}

	public function setAsRead($asRead) {
		$this->asRead = $asRead;
	}

}
