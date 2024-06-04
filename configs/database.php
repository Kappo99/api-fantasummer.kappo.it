<?php

    define("ONLINE_CONNECTION", true);
    
    // if (!isLocalhost())
    // {
    //     define("DB_HOST", "localhost");
    //     define("DB_USERNAME", "root");
    //     define("DB_NAME", "__");
    //     define("DB_PASSWORD", "");
    // }
    /* else */ if (ONLINE_CONNECTION)
    {
        define("DB_HOST", "localhost");
        define("DB_USERNAME", "u253831929_Summer");
        define("DB_PASSWORD", "dAE#B2D*v");
        define("DB_NAME", "u253831929_FantaSummer");
    }
    else
    {
        define("DB_HOST", "localhost");
        define("DB_USERNAME", "root");
        define("DB_NAME", "__");
        define("DB_PASSWORD", "");
    }
