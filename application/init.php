<?php
@session_start();

date_default_timezone_set('Africa/Lagos');
ini_set("display_errors",1);

include_once "definitions.php";
include_once PAYSTACK. "/Paystack.php";

function __autoload($className) {
    if (!strpos($className, "\\")) {
        $formats = array(
            "library/$className.php",
            "apis/$className.php",
            "models/$className.php",
            "controllers/$className.php",
            "traits/$className.php",
            "externals/phpmailer/$className.php"
        );

        foreach ($formats as $key => $value) {
            $file = APP. "/$value";
            if (file_exists($file)) {
                include_once "$value";
            }
        }
    } else {
        $realClassName = str_replace("\\", "/", $className);
        $resource = str_replace("Yabacon", "", $realClassName. ".php");
        include_once PAYSTACK. $resource;
    }
}

App::begin();

