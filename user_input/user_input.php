<?php

// http://localhost:8006/user_input.php

if (isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){                                 //check if X-Access-Token exists
    if ($_SERVER['HTTP_X_ACCESS_TOKEN'] == "SECRET_TOKEN"){                  //check if value of token is correct
    }
    else {
        echo "##################################".PHP_EOL;
        echo "Error: Bad X-Access-Token argument".PHP_EOL; 
        echo "##################################".PHP_EOL;
        die;
    }
}
else {
    echo "########################".PHP_EOL;
    echo "Error: No X-Access-Token".PHP_EOL;
    echo "########################".PHP_EOL;
    die;
}


if(isset($_SERVER["CONTENT_TYPE"])){                                     //check if content-type exists
    if($_SERVER["CONTENT_TYPE"] == "application/x-www-form-urlencoded"){ //check if content-type is correct
    }
    else {
        echo "#############################".PHP_EOL;
        echo "Wrong data type: Content-Type".PHP_EOL;
        echo "#############################".PHP_EOL;
        die;
    }
}
else {
    echo "#############################".PHP_EOL;
    echo "Error: No Content-Type header".PHP_EOL;
    echo "#############################".PHP_EOL;
    die;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST'){                                  //check if request method is post
}
else {
    echo "######################################".PHP_EOL;
    echo "Error: Wrong method, it should be POST".PHP_EOL;
    echo "######################################".PHP_EOL;
    die;
}


if ( array_key_exists('page', $_GET)){                                       //check if page is exists
    if ($_GET['page'] == '1'){
        echo "Requested page 1".PHP_EOL;
    }
    elseif ($_GET['page'] == '2'){
        echo "Requested page 2".PHP_EOL;
    }
    elseif ($_GET['page'] == '3'){
        echo "Requested page 3".PHP_EOL;
    }
    else {
        echo "######################################".PHP_EOL;
        echo "Wrong page argument, should be 1, 2, 3".PHP_EOL;
        echo "######################################".PHP_EOL;
        die;
    }
}
else {
    echo "#######################".PHP_EOL;
    echo "Error: No page variable".PHP_EOL;
    echo "#######################".PHP_EOL;
    die;
}


if(!empty($_POST)){

    echo "Number of data: "; 
    echo count(array_keys($_POST)).PHP_EOL;

    foreach ($_POST as $param_name => $param_val) {
        echo htmlentities("Value of $param_name : $param_val".PHP_EOL);
    }
}
else{
    echo "#########################".PHP_EOL;
    echo "Error: No data in request".PHP_EOL;
    echo "#########################".PHP_EOL;
    die;
}

?>
