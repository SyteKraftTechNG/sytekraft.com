<?php
class MenuItemsAPI extends API
{

	use CommonDataAPI;

	function __construct($uri) {
		parent::__construct($uri);
		$this->loadModel();
	}

}
