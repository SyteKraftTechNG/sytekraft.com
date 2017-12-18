<?php
class PageSectionIndex extends Model
{

	private $pageId;
	private $pageSectionId;
	private $precedingPageSectionId;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getPageId() {
		return $this->pageId;
	}

	public function setPageId($pageId) {
		$this->pageId = $pageId;
	}

	public function getPageSectionId() {
		return $this->pageSectionId;
	}

	public function setPageSectionId($pageSectionId) {
		$this->pageSectionId = $pageSectionId;
	}

	public function getPrecedingPageSectionId() {
		return $this->precedingPageSectionId;
	}

	public function setPrecedingPageSectionId($precedingPageSectionId) {
		$this->precedingPageSectionId = $precedingPageSectionId;
	}

}
