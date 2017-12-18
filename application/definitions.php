<?php

define("BASE_DIR", dirname(__DIR__));
define("DOC_ROOT", preg_replace("!${_SERVER['SCRIPT_NAME']}$!", '', $_SERVER['SCRIPT_FILENAME']));
define("BASE_URL", preg_replace("!^". DOC_ROOT. "!", '', BASE_DIR));

define("PROTOCOL", $_SERVER['REQUEST_SCHEME']);
define("PORT", $_SERVER['SERVER_PORT']);
define("DISP_PORT", (PROTOCOL == 'http' && PORT == 80 || PROTOCOL == 'https' && PORT == 443) ? '' : (":". PORT));

define("DOMAIN", $_SERVER['SERVER_NAME']);
define("APP_URL", PROTOCOL. "://". DOMAIN. DISP_PORT. BASE_URL);

define("APP", BASE_DIR. "/application");
define("PAYSTACK", APP. "/externals/Paystack");
define("VIEWS", APP. "/views");
define("FRONTEND", VIEWS. "/frontend");
define("BACKEND", VIEWS. "/backend");

define("ASSETS", BASE_URL. "/assets");
define("API", BASE_URL. "/api");
define("ADMIN_ALIAS", "office");
define("APP_NAME", "SyteKraft");

define("MEDIA_ROOT_SRC", BASE_DIR. "/media");
define("MEDIA_INDEX_URL", BASE_URL. "/media");

define("NOW", date("Y-m-d H:i:s"));
