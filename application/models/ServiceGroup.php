<?php
class ServiceGroup extends Model
{

	private $name;
	private $headline;
	private $overview;
	private $iconColor;
	private $active;

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

	public function getHeadline() {
		return $this->headline;
	}

	public function setHeadline($headline) {
		$this->headline = $headline;
	}

	public function getOverview() {
		return $this->overview;
	}

	public function setOverview($overview) {
		$this->overview = $overview;
	}

	public function getIconColor() {
		return $this->iconColor;
	}

	public function setIconColor($iconColor) {
		$this->iconColor = $iconColor;
	}

	public function getActive() {
		return $this->active;
	}

	public function setActive($active) {
		$this->active = $active;
	}

}
