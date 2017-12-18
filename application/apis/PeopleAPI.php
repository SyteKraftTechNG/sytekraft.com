<?php
class PeopleAPI extends API
{

	use CommonDataAPI;

	function __construct($uri) {
		parent::__construct($uri);
		$this->loadModel();
	}

}
