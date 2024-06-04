<?php

    function isLocalhost($whitelist = ['127.0.0.1', '::1']) {
        return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
    }

    require_once __DIR__ . "/paths.php";
    require_once __DIR__ . "/database.php";
    require_once __DIR__ . "/key.php";

    require_once PATH_DATABASE;
    
    DataBase::initialize();
