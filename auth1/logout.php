<?php

ini_set('error_log', __DIR__ . '/error.log');

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

// logout
function logout()
{   
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        my_log("Error: expected POST, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

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

    if (! isset($_SESSION['is_auth'])) {
        session_destroy();
        echo "<h1>Not authorised</h1>";
        my_log("Logout error: not authorised");
        print_r(json_encode(["status" => "error", "message" => "Failed to logout: not authorised"]));
        return http_response_code(400);
    }

    session_destroy();
    my_log("Logout: successful, session destroyed: " . session_id() . PHP_EOL);
    echo "<h1>Successful LogOut</h1>";
    return http_response_code(200);
}

logout();
