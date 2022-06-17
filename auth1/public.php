<?php

function my_log(string $error)
{
    error_log($error . PHP_EOL, 3, './error.log');
}

function create_connection($hostname, $username, $password, $dbname): PDO
{
    try {
        $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage();
        $dbh = null;
        die();
    }
}

// public
function public_res()
{   
    session_name('authcookie');
    session_set_cookie_params(
        [
            'lifetime' => 100,
            'httponly' => true,
            'sameSite' => 'strict'
        ]
    );

    ini_set('session.save_path', __DIR__.'/sessions');
    ini_set('session.use_strict_mode', true);
    ini_set('session.use_only_cookies', true);
    ini_set('session.referer_check', '');
    ini_set('session.cache_limiter', 'nocache');

    session_start();

    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        my_log("Error: expected POST, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }
    
    echo "<h1>Successful public request</h1>";
    return http_response_code(200);
}

public_res($dbh);
$dbh = null;
