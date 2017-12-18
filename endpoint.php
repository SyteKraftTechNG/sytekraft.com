<?php
/**
 * Created by PhpStorm.
 * User: EtimEsuOyoIta
 * Date: 12/1/17
 * Time: 11:15 AM
 */

include_once 'application/init.php';

$all = function(array $a) {
    return $a;
};

$requestData = $_REQUEST;
$filesData = $_FILES;
$phpInputJSON = file_get_contents("php://input");

$data = [];
parse_str($phpInputJSON, $data);

$input = array_merge($requestData, $filesData, $data);
unset($input['url']);

$url = substr($url, 4);
$endpoint = new EndpointAPI($url);

$endpoint->processRequest($_SERVER['REQUEST_METHOD'], $input);
$endpoint->getResponse(true);
