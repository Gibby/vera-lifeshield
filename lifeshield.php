<?php

// Define all functions first
// All functions will get the global config
// Probably a better way to do that part, but hey...
// I am not a php guy at all....

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

function csv_to_array($filename='', $delimiter=',') {
// Get global config
global $config;

    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
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

function set_vera ($url) {
// Get global config
global $config;

    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;

}

function check_faulted () {
// Get global config
global $config;

    // target for status
    $targeturl = ($config['FAULTED_URL']);

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
    $file = 'sensors.out';
    file_put_contents($file, $content);
    return $content;

}

// Get settings from ini file
$ini_array = parse_ini_file("config.ini.php", true);

// Create global CONFIG array
$config = ($ini_array['CONFIG']);
$VERA_IP = ($config['VERA_IP']);
$VERA_URL = "http://$VERA_IP:3480/data_request?id=variableset&DeviceNum=";
$TRIPPED_URL = ($config['TRIPPED_URL']);
$CLEAR_URL = ($config['CLEAR_URL']);

// Change to base directory
chdir ($config['BASE']);

// Now lets loopy loop ;)
while(file_exists($config['LOCK_FILE']) !== true) {

    //Check if lock file exists
    check_lock();

    // Check alarm status
    $content = ls_login();
    $layoutArray[] = json_decode($content, TRUE);
    $status = ucfirst($layoutArray[0]['status']['@code']);

    // Set my date format
    $mydate = date(($config['DATE_FORMAT']));

    // Set system status in Vera
    $status = ucfirst($layoutArray[0]['data']['armstate']);
    if ($status == "Ready") {
        $status = "Ready";
    }
    if ($status == "Armstayinst") {
        $status = "Instant";
    }
    if ($status == "Armstay") {
        $status = "Stay";
    }
    if ($status == "Armaway") {
        $status = "Away";
    }
    $dev_num = ($config['VERA_STATUS_ID']);
    $var_num = ($config['VERA_STATUS_VAR']);
    $service_id = ($config['VERA_STATUS_SERVICE_ID']);
    $set_url = "$VERA_URL$dev_num$service_id&Variable=Variable$var_num&Value=$status";
    $returncontent = set_vera("$set_url");
    $status = ($config['VERA_STATUS_NAME']);
    $set_url = "$VERA_URL$dev_num$service_id&Variable=VariableName$var_num&Value=$status";
    $returncontent = set_vera("$set_url");
    unset($dev_num);

    // Set future system status in Vera
    $status = ucfirst($layoutArray[0]['data']['futureArmstate']);
    if ($status == "Ready") {
        $status = "Ready";
    }
    if ($status == "Armstayinst") {
        $status = "Instant";
    }
    if ($status == "Armstay") {
        $status = "Stay";
    }
    if ($status == "Armaway") {
        $status = "Away";
    }
    $dev_num = ($config['VERA_FUTURE_STATUS_ID']);
    $var_num = ($config['VERA_FUTURE_STATUS_VAR']);
    $service_id = ($config['VERA_FUTURE_STATUS_SERVICE_ID']);
    $set_url = "$VERA_URL$dev_num$service_id&Variable=Variable$var_num&Value=$status";
    $returncontent = set_vera("$set_url");
    $status = ($config['VERA_FUTURE_STATUS_NAME']);
    $set_url = "$VERA_URL$dev_num$service_id&Variable=VariableName$var_num&Value=$status";
    $returncontent = set_vera("$set_url");
    unset($dev_num);

    // Set update time in vera with my date format
    $status = $mydate;
    $dev_num = ($config['VERA_DATE_ID']);
    $var_num = ($config['VERA_DATE_VAR']);
    $service_id = ($config['VERA_DATE_SERVICE_ID']);
    $set_url = "$VERA_URL$dev_num$service_id&Variable=Variable$var_num&Value=$status";
    $returncontent = set_vera("$set_url");
    $status = ($config['VERA_DATE_NAME']);
    $set_url = "$VERA_URL$dev_num$service_id&Variable=VariableName$var_num&Value=$status";
    $returncontent = set_vera("$set_url");
    unset($dev_num);

    //Update sensors for static file for vera
    // Sensor names and ID's in vera
    $sensorsCSV = csv_to_array(($config['CSV_FILE']));
    $sensors = array();
    foreach ($sensorsCSV as $sensor) {
        $sensors[$sensor["devnum"]] = $sensor["name"];
    }

    // Check for faulted sensors
    $sensor_content = check_faulted();

    // Set tripped sensors status in Vera
    $sensor_contentArray[] = json_decode($sensor_content, TRUE);
    //print_r ($sensor_contentArray);
    $untriggered = $sensors;
    foreach ($sensor_contentArray[0]['data'] as $sensorArray) {
        foreach ($sensorArray as $sensor) {
            $dev_num = array_search($sensor["@name"], $sensors);
            unset($untriggered[$dev_num]);
            $set_url = "$VERA_URL$dev_num$TRIPPED_URL";
            $returncontent = set_vera("$set_url");
        }
    }
    unset($dev_num);

    // Set cleared sensors status in Vera
    foreach ($untriggered as $dev_num => $sensor) {
        $set_url = "$VERA_URL$dev_num$CLEAR_URL";
        $returncontent = set_vera("$set_url");
    }
    unset($dev_num);


    // Sleep then do it all over again
    sleep($config['SLEEP']);
    unset($layoutArray);
    unset($sensor_content);
    unset($untriggered);
    unset($sensor_contentArray);
    unset($sensorArray);
    unset($sensors);
    unset($sensorsCSV);


}



?>
