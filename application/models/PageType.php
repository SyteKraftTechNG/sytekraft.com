<?php
class PageType extends Model
{

	private $name;
	private $viewTemplateSrc;

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

	public function getViewTemplateSrc() {
		return $this->viewTemplateSrc;
	}

	public function setViewTemplateSrc($viewTemplateSrc) {
		$this->viewTemplateSrc = $viewTemplateSrc;
	}

}
