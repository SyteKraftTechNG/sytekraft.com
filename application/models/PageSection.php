<?php
class PageSection extends Model
{

	private $title;
	private $viewTemplateSrc;
	private $showTitle;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getViewTemplateSrc() {
		return $this->viewTemplateSrc;
	}

	public function setViewTemplateSrc($viewTemplateSrc) {
		$this->viewTemplateSrc = $viewTemplateSrc;
	}

	public function getShowTitle() {
		return $this->showTitle;
	}

	public function setShowTitle($showTitle) {
		$this->showTitle = $showTitle;
	}

}
