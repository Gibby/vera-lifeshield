<?php


function lock_found () {
// Get global config
global $config;

    exit(1);

}

function check_lock () {
// Get global config
global $config;

    // Check if locked file is in place
    // if it is there, you must make sure your
    // account is not locked else just
    if(file_exists($config['LOCK_FILE'])) {
        lock_found();
    }

}


function ls_login () {
// Get global config
global $config;

    // Check lock file before every login
    check_lock();

    // Get AUTH variables from config
    $ini_array = parse_ini_file("config.ini.php", true);
    $auth = ($ini_array['AUTH']);
    $loginurl = ($auth['LOGIN_URL']);
    $username = ($auth['USER']);
    $password = ($auth['PASS']);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $loginurl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'username=' . $username .'&password=' . $password . '&apiVersion=LV3_R4&responseFormat=json');
    // get target url contents:
    $content = curl_exec($ch);
    curl_close ($ch);
    $file = 'login.status';
    file_put_contents($file, $content);

    return $content;

}

function disarm () {
// Get global config
global $config;

    // Check lock file before every login
    check_lock();

    // Get AUTH variables from config
    $ini_array = parse_ini_file("config.ini.php", true);
    $auth = ($ini_array['AUTH']);
    $targeturl = ($auth['DISARM_URL']);

    // initialize curl instance
    $ch = curl_init();

    // use cookie
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

    // enable return transfer:
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    // set URL to target content:
    curl_setopt($ch, CURLOPT_URL, $targeturl);

    // get target url contents:
    $content = curl_exec($ch);
    curl_close ($ch);
    echo $content;

}

function arm_stay () {
// Get global config
global $config;

    // Check lock file before every login
    check_lock();

    // Get AUTH variables from config
    $ini_array = parse_ini_file("config.ini.php", true);
    $auth = ($ini_array['AUTH']);
    $loginurl = ($auth['LOGIN_URL']);
    $targeturl = ($auth['STAY_URL']);

    // initialize curl instance
    $ch = curl_init();

    // use cookie
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

    // enable return transfer:
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    // set URL to target content:
    curl_setopt($ch, CURLOPT_URL, $targeturl);

    // get target url contents:
    $content = curl_exec($ch);
    curl_close ($ch);
    echo $content;

}

function arm_away () {
// Get global config
global $config;

    // Check lock file before every login
    check_lock();

    // Get AUTH variables from config
    $ini_array = parse_ini_file("config.ini.php", true);
    $auth = ($ini_array['AUTH']);
    $loginurl = ($auth['LOGIN_URL']);
    $targeturl = ($auth['AWAY_URL']);

    // initialize curl instance
    $ch = curl_init();

    // use cookie
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

    // enable return transfer:
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    // set URL to target content:
    curl_setopt($ch, CURLOPT_URL, $targeturl);

    // get target url contents:
    $content = curl_exec($ch);
    curl_close ($ch);
    echo $content;

}

function arm_instant () {
// Get global config
global $config;

    // Check lock file before every login
    check_lock();

    // Get AUTH variables from config
    $ini_array = parse_ini_file("config.ini.php", true);
    $auth = ($ini_array['AUTH']);
    $loginurl = ($auth['LOGIN_URL']);
    $targeturl = ($auth['INST_URL']);

    // initialize curl instance
    $ch = curl_init();

    // use cookie
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

    // enable return transfer:
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    // set URL to target content:
    curl_setopt($ch, CURLOPT_URL, $targeturl);

    // get target url contents:
    $content = curl_exec($ch);
    curl_close ($ch);
    echo $content;

}

if(function_exists($_GET['f'])) {
    // Get settings from ini file
    $ini_array = parse_ini_file("config.ini.php", true);

    // Create global CONFIG array
    $config = ($ini_array['CONFIG']);

    // Change to base directory
    chdir ($config['BASE']);

    // Check if lock file exists
    check_lock();

    // Make sure logged in
    $content = ls_login();
    $returned = $_GET['f']();
}

echo $returned

?>
