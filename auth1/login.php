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

function login()
{

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        security_log("Error: expected POST");
        return http_response_code(405);
    }

    if (!(array_key_exists('username', $_POST) and is_string($_POST['username']) and
        array_key_exists('password', $_POST) and is_string($_POST['password'])
    )) {
        print_r(json_encode(["status" => "error", "message" => "Failed to login: malformed request"]));
        security_log("Login error: malformed request");
        return http_response_code(400);
    }

    $dbh = create_connection('localhost', 'user', 'pass', 'library');
    $sql = "SELECT hash_pass FROM users WHERE username = :username;";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':username' => $_POST['username']
    ));
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (count($res) != 1) {
        print_r(json_encode(["status" => "error", "message" => "Failed to login: no such user"]));
        my_log("Login error: no such user");
        return http_response_code(400);
    }

    $hash = $res[0]['hash_pass'];
    if (!password_verify($_POST['password'], $hash)) {
        print_r(json_encode(["status" => "error", "message" => "Invalid credentials"]));
        my_log("Login error: wrong password");
        return http_response_code(200);
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

    my_log("login: session started = " . session_id() . PHP_EOL);
    session_regenerate_id($delete_old_session = true);
    my_log("login: session regenerated = " . session_id() . PHP_EOL);

    $_SESSION['is_auth'] = true;

    echo "<h1>Session successfully created </h1>";
    echo "Your code is " . session_id() .PHP_EOL;
    my_log("Login: successful");

    return http_response_code(200);
}

login();
