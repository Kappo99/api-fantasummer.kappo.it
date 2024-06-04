<?php

define("SERVER_ROOT", __DIR__ . "/../");
define("RESERVED_AREA_ROOT", SERVER_ROOT . "../../area-riservata/");
define("WEBSITE_ROOT", SERVER_ROOT . "../../");

define('API_DIR', ''); // /api/v1

if (isLocalhost()) {
    define("SERVER_DOMAIN", "localhost");
    define("SERVER_PROTOCOL", "http");
} else {
    define("SERVER_DOMAIN", "api.prenota-azione.com");
    define("SERVER_PROTOCOL", "https");
}

define("PATH_QUERY", SERVER_ROOT . "database/query.php");
define("PATH_DATABASE", SERVER_ROOT . "database/database.php");

define("PATH_IMAGES_BLOG", WEBSITE_ROOT . "assets/images/blog/");
