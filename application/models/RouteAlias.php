<?php
class RouteAlias extends Model
{

	private $url;
	private $trueURL;

	use CommonDataModel;

	function __construct($id = 0) {
		parent::__construct(__CLASS__, $id);
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	public function getTrueURL() {
		return $this->trueURL;
	}

	public function setTrueURL($trueURL) {
		$this->trueURL = $trueURL;
	}

	public static function findTrueURI($url) {
		$routeAlias = new RouteAlias();
		return $routeAlias->addParameter('url', $url)->findSingle()->getTrueURL();
	}

}
