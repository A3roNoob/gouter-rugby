<?php
require_once("config.php");
function connectToDb()
{
    global $config;
    try {
        $db = new PDO('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'].';port=3306', $config['db']['username'], $config['db']['password']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo '{"Code" : "' . $GLOBALS['CODE'] ['CODE_3']['Code'] . '", "Message" : "' . $GLOBALS['CODE'] ['CODE_3']['Message'] . '", "INFOS" : "'. $e->getMessage() .'"}';
        exit(1);
    }
    return $db;
}

function accessForbidden()
{
    echo "<div class='alert alert-danger'>Acc&egrave;s interdit</div>";
    include_once(TEMPLATES_PATH . '/footer.php');
    exit(403);
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function convertDateToSql($date)
{
    Try {
        if (!is_a($date, "DateTime")) {
            if (strpos($date, '/'))
                $date = DateTime::createFromFormat("d/m/Y", $date);
            else if (strpos($date, '-'))
                $date = DateTime::createFromFormat("d-m-Y", $date);
        }
        $date = $date->format("Y-m-d");

    } catch (Exception $e) {
        echo $e->getMessage();
    }

    return $date;
}

function convertDateFromSql($date)
{
    Try {
        if (!is_a($date, "DateTime")) {
            if (strpos($date, '/'))
                $date = DateTime::createFromFormat("d/m/Y", $date);
            else if (strpos($date, '-'))
                $date = DateTime::createFromFormat("Y-m-d", $date);
        }
        $date = $date->format("d-m-Y");
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return $date;
}

function today()
{
    return date("d-m-Y");
}

function compareDate($date1, $date2)
{
    echo "<br />";
    $d1ex = explode("-", $date1);
    $d2ex = explode("-", $date2);

    if ($d2ex[2] > $d1ex[2]) {
        return true;
    } else {
        if ($d2ex[1] >= $d1ex[1]) {
            return true;
        } else {
            if ($d2ex[0] >= $d1ex[0])
                return true;
        }
    }

    return false;
}

function isPostSet($var)
{
    return (isset($_POST[$var]) && !empty($_POST[$var]));
}

function isGetSet($var)
{
    return (isset($_GET[$var]) && !empty($_GET[$var]));

}

function testDate($date)
{
    try {
        $d = DateTime::createFromFormat("d-m-Y", $date);
        $d = $d->format("d-m-y");
    } catch (Exception $e) {
        return 1;
    }
    return $d;

}

error_reporting(0);

function shutDownFunction()
{
    $error = error_get_last();
    // fatal error, E_ERROR === 1
    if ($error['type'] === E_ERROR) {
        errorHandler(E_ERROR, $error['message'], $error['file'], $error['line']);
        exit(1);
    }
}

register_shutdown_function('shutDownFunction');

function errorHandler($code, $message, $file, $line)
{
    echo '{"Code" : ' . $code . ', "Message" : "' . $message . '", "File" : "' . addslashes($file) . '", "Line" : ' . $line . '}';
    exit(1);
}

set_error_handler('errorHandler');

function htmltojson($json){
    return str_replace('&quot;', '"', $json);
}

function var_dump_pre($mixed = null) {
    echo '<pre>';
    var_dump($mixed);
    echo '</pre>';
    return null;
}