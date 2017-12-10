<style>
    html,
    body {
        background: #efefef;
        padding: 10px;
        font-family: 'Varela Round';
    }

    a {
        color: #aaaaaa;
        transition: all ease-in-out 200ms;
    }

    a:hover {
        color: #333333;
        text-decoration: none;
    }

    .etc-login-form p {
        margin-bottom: 5px;
    }

    .login-form-1 {
        max-width: 300px;
        border-radius: 5px;
        display: inline-block;
    }

    .main-login-form {
        position: relative;
    }

    .login-form-1 .form-control {
        border: 0;
        box-shadow: 0 0 0;
        border-radius: 0;
        background: transparent;
        color: #555555;
        padding: 7px 0;
        font-weight: bold;
        height: auto;
    }

    .login-form-1 .form-control::-webkit-input-placeholder {
        color: #999999;
    }

    .login-form-1 .form-control:-moz-placeholder,
    .login-form-1 .form-control::-moz-placeholder,
    .login-form-1 .form-control:-ms-input-placeholder {
        color: #999999;
    }

    .login-form-1 .form-group {
        margin-bottom: 0;
        border-bottom: 2px solid #efefef;
        padding-right: 20px;
        position: relative;
    }

    .login-form-1 .form-group:last-child {
        border-bottom: 0;
    }

    .login-group {
        background: #ffffff;
        color: #999999;
        border-radius: 8px;
        padding: 10px 20px;
    }

    .login-form-1 .login-button {
        position: absolute;
        right: -25px;
        top: 50%;
        background: #ffffff;
        color: #999999;
        padding: 11px 0;
        width: 50px;
        height: 50px;
        margin-top: -25px;
        border: 5px solid #efefef;
        border-radius: 50%;
        transition: all ease-in-out 500ms;
    }

    .login-form-1 .login-button:hover {
        color: #555555;
        transform: rotate(450deg);
    }

    .login-form-1 .login-button.clicked {
        color: #555555;
    }

    .login-form-1 .login-button.clicked:hover {
        transform: none;
    }

    .login-form-1 .login-button.clicked.success {
        color: #2ecc71;
    }

    .login-form-1 .login-button.clicked.error {
        color: #e74c3c;
    }

    label.form-invalid {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 5;
        display: block;
        margin-top: -25px;
        padding: 7px 9px;
        background: #777777;
        color: #ffffff;
        border-radius: 5px;
        font-weight: bold;
        font-size: 11px;
    }

    label.form-invalid:after {
        top: 100%;
        right: 10px;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
        border-color: transparent;
        border-top-color: #777777;
        border-width: 6px;
    }

    .login-form-main-message {
        background: #ffffff;
        color: #999999;
        border-left: 3px solid transparent;
        border-radius: 3px;
        margin-bottom: 8px;
        font-weight: bold;
        height: 0;
        padding: 0 20px 0 17px;
        opacity: 0;
        transition: all ease-in-out 200ms;
    }

    .login-form-main-message.show {
        height: auto;
        opacity: 1;
        padding: 10px 20px 10px 17px;
    }

    .login-form-main-message.success {
        border-left-color: #2ecc71;
    }

    .login-form-main-message.error {
        border-left-color: #e74c3c;
    }

    /* hover style just for information */
    label:hover:before {
        border: 1px solid #f6f6f6 !important;
    }

    /*=== Customization ===*/
    /* radio aspect */
    [type="checkbox"]:not(:checked) + label:before,
    [type="checkbox"]:checked + label:before {
        border-radius: 3px;
    }

    [type="radio"]:not(:checked) + label:before,
    [type="radio"]:checked + label:before {
        border-radius: 35px;
    }

    /* selected mark aspect */
    [type="checkbox"]:not(:checked) + label:after,
    [type="checkbox"]:checked + label:after {
        content: '✔';
        top: 0;
        left: 2px;
        font-size: 14px;
    }

    [type="radio"]:not(:checked) + label:after,
    [type="radio"]:checked + label:after {
        content: '\2022';
        top: 0;
        left: 3px;
        font-size: 30px;
        line-height: 25px;
    }

    /*=== 9. Misc ===*/
    .logo {
        padding: 15px 0;
        font-size: 25px;
        color: #aaaaaa;
        font-weight: bold;
    }

</style>
<!-- All the files that are required -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

<?php
$host = $db = $user = $pwd = "";
$hostErr = $dbErr = $userErr = $pwdErr = false;

function executeCreationDb($filesql, PDO $db)
{
    $query = file_get_contents($filesql);
    $array = explode(";", $query);
    $fail = "";
    for ($i = 0; $i < count($array); $i++) {
        $str = $array[$i];
        if ($str != '') {
            $str .= ';';
            $envoi = $db->prepare($str);
            try {
                $envoi->execute();
            } catch (PDOException $e) {
                $failErr = $e->getCode() != 42000;
                if ($failErr) {
                    $arr = explode("'", $e->getMessage());
                    if (isset($arr[1]))
                        $fail .= $arr[1] . ", ";
                }
                $fail = $e->getMessage();
            }
        }
    }
    return $fail;
}

function executeQueryFile($filesql, PDO $db)
{
    $query = file_get_contents($filesql);
    $array = explode(";", $query);
    $fail = "";
    for ($i = 0; $i < count($array); $i++) {
        $str = $array[$i];
        if ($str != '') {
            $str .= ';';
            $envoi = $db->prepare($str);
            try {
                $envoi->execute();
            } catch (PDOException $e) {
                return $e->getMessage();
            }
        }
    }
    return $fail;
}

function executeTriggerVueFile($filesql, PDO $db)
{
    $query = file_get_contents($filesql);
    $array = explode("DELIMITER ;", $query);
    $fail = "";
    for ($i = 0; $i < count($array); $i++) {
        $str = $array[$i];
        if ($str != '') {
            $str .= ';';
            $envoi = $db->prepare($str);
            try {
                $envoi->execute();
            } catch (PDOException $e) {
                return $e->getMessage();
            }
        }
    }
    return $fail;
}

$showErr = $showInfoErr = "";

$form = " <div class=\"text-center\" style=\"padding:50px 0; margin-left: 40%;\">

            <div class=\"logo\">Base de donn&eacute;es:&nbsp;</div>
            <!-- Main Form -->
            <div class=\"login-form-1\">
                <form method=\"post\" id=\"register-form\" class=\"text-left\">
                    <div class=\"login-form-main-message $showErr\" >$showInfoErr</div>
                    <div class=\"main-login-form\">
                        <div class=\"login-group\">

                            <div class=\"form-group\">
                                <label for=\"reg_basename\" class=\"sr-only\">Nom de la base:&nbsp;</label>
                                <input type=\"text\" class=\"form-control\" id=\"reg_basename\" name=\"reg_basename\"
                                       placeholder=\"Nom de la base\">
                            </div>
                            <div class=\"form-group\">
                                <label for=\"reg_dbhost\" class=\"sr-only\">H&ocirc;te de la base:&nbsp;</label>
                                <input type=\"text\" class=\"form-control\" id=\"reg_dbhost\" name=\"reg_dbhost\"
                                       placeholder=\"H&ocirc;te\">
                            </div>
                            <div class=\"form-group\">
                                <label for=\"reg_login\" class=\"sr-only\">Identifiant:&nbsp;</label>
                                <input type=\"text\" class=\"form-control\" id=\"reg_login\" name=\"reg_login\"
                                       placeholder=\"Identifiant\">
                            </div>
                            <div class=\"form-group\">
                                <label for=\"reg_password\" class=\"sr-only\">Mot&nbsp;de&nbsp;passe:&nbsp;</label>
                                <input type=\"password\" class=\"form-control\" id=\"reg_password\" name=\"reg_password\"
                                       placeholder=\"Mot de passe\">
                            </div>
                        </div>
                        <button type=\"submit\" class=\"login-button\"><i class=\"fa fa-chevron-right\"></i></button>
                    </div>
                </form>
            </div>
            <!-- end:Main Form -->
        </div>
";


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$file = dirname(__FILE__) . "/credentials.json";
if (file_exists($file)) {
    $credentials = file_get_contents($file, FILE_USE_INCLUDE_PATH);
    $data = json_decode($credentials, true);
    echo "L'installation est terminée. Veuillez supprimer ce fichier et acc&eacute;der à <a href='/'> l'accueil</a>";
    exit(1);
} else {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['reg_dbhost']) && !empty($_POST['reg_dbhost']))
            $host = test_input($_POST['reg_dbhost']);
        else
            $hostErr = true;
        if (isset($_POST['reg_basename']) && !empty($_POST['reg_basename']))
            $db = test_input($_POST['reg_basename']);
        else
            $dbErr = true;
        if (isset($_POST['reg_login']) && !empty($_POST['reg_login']))
            $user = test_input($_POST['reg_login']);
        else
            $userErr = true;
        if (isset($_POST['reg_password']) && !empty($_POST['reg_password']))
            $pwd = test_input($_POST['reg_password']);
        else
            $pwdErr = true;

        if (!$hostErr && !$dbErr && !$userErr && !$pwdErr) {
            $data = array(
                "dbname" => $db,
                "dbhost" => $host,
                "username" => $user,
                "password" => $pwd
            );

            $json = json_encode($data);
            $fail = false;
            try {
                $db = new PDO('mysql:host=' . $data['dbhost'] . ';dbname=' . $data['dbname'], $data['username'], $data['password']);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo '&Eacute;chec lors de la connexion : ' . $e->getMessage();
                $db = null;
                $fail = true;
            }
            if (!$fail) {
                echo "Ex&eacute;cution du script sql pour g&eacute;n&eacute;rer la base de donnée.<br />";
                $fail_creat = executeCreationDb("..\\sql\\creation_base.sql", $db);
                if (strlen($fail_creat) > 0) {
                    echo "Une erreur s'est produite, v&eacute;rifiez qu'aucune de vos tables d&eacute;j&agrave; pr&eacute;sentes ne comportent les noms: $fail !<br />";
                }

                $fail_inser = executeQueryFile("..\\sql\\insertion.sql", $db);
                if (strlen($fail_inser) > 0) {
                    echo "Une erreur s'est produite (insertion): $fail<br />";
                }

                $fail_trigger = executeTriggerVueFile("..\\sql\\trigger.sql", $db);
                if (strlen($fail_trigger) > 0) {
                    echo "Une erreur s'est produite(trigger): $fail<br />";
                }

                $fail_vues = executeTriggerVueFile("..\\sql\\Vue.sql", $db);
                if (strlen($fail_vues) > 0) {
                    echo "Une erreur s'est produite(vue): $fail<br />";
                }

                if (strlen($fail_creat) <= 0 && strlen($fail_inser) <= 0 && strlen($fail_trigger) <= 0 && strlen($fail_vues) <= 0) {
                    file_put_contents($file, $json);
                    if (file_exists($file)) {
                        echo "Tout s'est d&eacute;roul&eacute; avec succ&eacute;s.<br />L'installation est termin&eacute;e. Veuillez supprimer ce fichier et acc&eacute;der à <a href='/'> l'acceuil</a>";
                    } else {
                        echo "Le fichier credentials.json n'a pas pu &ecirc;tre cr&eacute;&eacute;.<br >V&eacute;rifiez les permissions.";
                    }
                }
            }

        } else {
            if ($dbErr || $hostErr || $userErr || $pwdErr) {
                $showErr = "show error";
                $showInfoErr = "L'une de vos informations est incorrecte.";
            } else {
                $showInfoErr = $showErr = "";

            }
            echo $form;
        }
    } else {
        echo $form;

    }
}