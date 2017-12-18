<?php
class MenuItem extends Model
{

	private $linkText;
	private $menuId;
	private $parentId;
	private $slug;
	private $isHomePage;
	private $isMega;
	private $megaDescription;
	private $megaImageSrc;
	private $activeFromDate;
	private $activeUntilDate;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getLinkText() {
		return $this->linkText;
	}

	public function setLinkText($linkText) {
		$this->linkText = $linkText;
	}

	public function getMenuId() {
		return $this->menuId;
	}

	public function setMenuId($menuId) {
		$this->menuId = $menuId;
	}

	public function getParentId() {
		return $this->parentId;
	}

	public function setParentId($parentId) {
		$this->parentId = $parentId;
	}

	public function getSlug() {
		return $this->slug;
	}

	public function setSlug($slug) {
		$this->slug = $slug;
	}

	public function getIsHomePage() {
		return $this->isHomePage;
	}

	public function setIsHomePage($isHomePage) {
		$this->isHomePage = $isHomePage;
	}

	public function getIsMega() {
		return $this->isMega;
	}

	public function setIsMega($isMega) {
		$this->isMega = $isMega;
	}

	public function getMegaDescription() {
		return $this->megaDescription;
	}

	public function setMegaDescription($megaDescription) {
		$this->megaDescription = $megaDescription;
	}

	public function getMegaImageSrc() {
		return $this->megaImageSrc;
	}

	public function setMegaImageSrc($megaImageSrc) {
		$this->megaImageSrc = $megaImageSrc;
	}

	public function getActiveFromDate() {
		return $this->activeFromDate;
	}

	public function setActiveFromDate($activeFromDate) {
		$this->activeFromDate = $activeFromDate;
	}

	public function getActiveUntilDate() {
		return $this->activeUntilDate;
	}

	public function setActiveUntilDate($activeUntilDate) {
		$this->activeUntilDate = $activeUntilDate;
	}

}
