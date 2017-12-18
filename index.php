<?php
/**
 * Created by PhpStorm.
 * User: EtimEsuOyoIta
 * Date: 12/1/17
 * Time: 11:15 AM
 */

include_once 'application/init.php';

$url = array_key_exists('url', $_REQUEST) ? $_REQUEST['url'] : 'index';
$first4 = substr($url, 0, 4);

if ($first4 == 'api/') {
    include_once 'endpoint.php';
} else {

}
