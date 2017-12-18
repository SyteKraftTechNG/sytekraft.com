<?php
class Page extends Model
{

	private $name;
	private $headline;
	private $overview;
	private $callToAction;
	private $pageModeId;
	private $targetHref;
	private $pageTypeId;
	private $backgroundImageSrc;
	private $colourOverlay;
	private $isActive;
	private $isHome;

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

	public function getCallToAction() {
		return $this->callToAction;
	}

	public function setCallToAction($callToAction) {
		$this->callToAction = $callToAction;
	}

	public function getPageModeId() {
		return $this->pageModeId;
	}

	public function setPageModeId($pageModeId) {
		$this->pageModeId = $pageModeId;
	}

	public function getTargetHref() {
		return $this->targetHref;
	}

	public function setTargetHref($targetHref) {
		$this->targetHref = $targetHref;
	}

	public function getPageTypeId() {
		return $this->pageTypeId;
	}

	public function setPageTypeId($pageTypeId) {
		$this->pageTypeId = $pageTypeId;
	}

	public function getBackgroundImageSrc() {
		return $this->backgroundImageSrc;
	}

	public function setBackgroundImageSrc($backgroundImageSrc) {
		$this->backgroundImageSrc = $backgroundImageSrc;
	}

	public function getColourOverlay() {
		return $this->colourOverlay;
	}

	public function setColourOverlay($colourOverlay) {
		$this->colourOverlay = $colourOverlay;
	}

	public function getIsActive() {
		return $this->isActive;
	}

	public function setIsActive($isActive) {
		$this->isActive = $isActive;
	}

	public function getIsHome() {
		return $this->isHome;
	}

	public function setIsHome($isHome) {
		$this->isHome = $isHome;
	}

}
