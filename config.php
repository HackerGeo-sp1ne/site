
<!--test

poza profil pe imgur

-->


<?php
    spl_autoload_register(function ($class_name) {
        require (isset($use_directory) ? "../" : "")."includes/classes/".$class_name.".php";
    });

    //SESSION
    session_start();
    ob_start();
    $start_time = microtime(TRUE);

    // SITE PRESETS
//    define('SITE_ROOT', dirname(dirname(dirname(__FILE__)))); // DO NOT CHANGE
    define('SITE_ROOT', dirname(__FILE__)); // DO NOT CHANGE new

    define('SITE_URL', 'https://'.$_SERVER['SERVER_NAME']); // DO NOT CHANGE

    // Website Name
    define('SITE_NAME', 'StayFrosty');

    // Website Description
    define('SITE_DESC', 'Working for panel');

    // Website Developer
    define('DEVELOPER', 'HackerGeo');
    define('DEVELOPER_INSTAGRAM', 'hacker.geo');

    // MAX GROUPS
    define('MAX_admin', 2); // max number of admins
    define('MAX_helper', 3); // max number of helpers

    // OTHERS
    define('SNOW_FALL', true); // snow on web
    define('GLOBAL_PROMOTION', 15); // % PERCENT
    define('MONEY_GOAL', 150); // MONEY GOAL
    define('MAX_UPLOAD_SIZE', 1500000);
    define('LAST_ONLINE_CHECK', (2*60)); //last online time check ex: 5*60 = 5 minutes

    $groups_sets = [
        "developer" => [
            'name' => 'Developer',
            'color' => '#FF00FF',
            'icon' => 'fa fa-code',
            'permissions' => [
                "dev",
                "staff",
                'staff.delete',
                'bans',
                "administration.problems",
                "administration.problems.delete",
                "administration.tools",
                "administration.announce",
                "global.view",
                "store.edit"
            ]
        ],
        "admin" => [
            'name' => 'Administrator',
            'color' => '#f44336',
            'icon' => 'fa fa-star',
            'permissions' => [
                'staff.view',
                "administration.problems",
                "global.view",
            ]
        ],
        "helper" => [
            'name' => 'Helper',
            'color' => 'green',
            'icon' => 'fa fa-star-half-o',
            'permissions' => [
                'staff.view',
                "global.view"
            ]
        ],
        "seller" => [
            'name' => 'Seller Access',
            'color' => '#00C6C9',
            'icon' => 'fa fa-check-square',
            'permissions' => [
                'staff.view',
                "global.view"
            ]
        ],
        "value" => [
            'name' => '#Value',
            'color' => 'green',
            'icon' => 'fa fa-money',
            'permissions' => [
                'staff.view',
                "global.view"
            ]
        ],
        "king" => [
            'name' => 'The King',
            'color' => 'purple',
            'icon' => 'fa fa-user-secret',
            'permissions' => [
                'staff.view',
                "global.view"
            ]
        ]
    ];

    //$default_groups = '{"groups":{"developer":false,"helper":false,"admin":false}}';
    $default_groups = '{"groups":{';
    foreach($groups_sets as $gr_name => $gr_bool){
        $default_groups=$default_groups.' "'.$gr_name.'":false,  ';
    }
    $default_groups=$default_groups.'"user":true}}';

    $utils = new Utils();

    $db_details = [
        'DB_HOST' => 'localhost',
        'DB_USER' => 'root',
        'DB_DATABASE' => 'panel',
        'DB_PASSWORD' => '123'
    ];

    /*
    // DataBase Details
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_DATABASE', 'panel');
    define('DB_PASSWORD', '');
    $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    */
    try
    {
        $mysqli = mysqli_connect($db_details['DB_HOST'], $db_details['DB_USER'], $db_details['DB_PASSWORD'], $db_details['DB_DATABASE']);
    }
    catch(Exception $e)
    {
        Utils::add_problem("DataBase connect failed [".$e->getMessage()."]",3);
        printf("Connect failed: %s\n", $e->getMessage());
        exit();
    }


    define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
    echo ENVIRONMENT;
    switch (ENVIRONMENT) {
        case 'development':
            error_reporting(-1);
            ini_set('display_errors', 1);
            break;

        case 'testing':
        case 'production':
            ini_set('display_errors', 0);
            if (version_compare(PHP_VERSION, '5.3', '>=')) {
                error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
            } else {
                error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
            }
            break;

        default:
            header('HTTP/1.1 503 Service Unavailable.', true, 503);
            echo 'The application environment is not set correctly.';
            exit(1); // EXIT_ERROR
    }

    if($_SERVER['HTTP_HOST'] == 'localhost')
    {
        die('Localhost');
    }

    date_default_timezone_set('Europe/Bucharest');


    $inj = new SQLinjectionDetect();
    $inj->detect();
?>

