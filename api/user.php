<?php
require_once("config.php");
require_once("functions.php");
if (isGetSet("action") && $_GET['action'] == "connexion") {
    if (isPostSet('login') && isPostSet('mdp')) {
        $login = test_input($_POST['login']);
        $mdp = test_input($_POST['mdp']);
        $user = Adulte::Connexion($login, $mdp);
        if ($user == null) {
            exit(1);
        }
        $user->generateToken();
        echo '{"Code" : "' . $GLOBALS['CODE']['CODE_0']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", "Nom" : "' . $user->getNom() . '", "Prenom" : "' . $user->getPrenom() . '", "Login" : "' . $user->getMail() . '", "Token" : "' . $user->getToken() . '"}';
    } else {
        echo '{"Code" : "' . $GLOBALS['CODE']['CODE_1']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet("action") && $_GET['action'] == "auth") {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_0']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '"}';
        } else {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_8']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code" : "' . $GLOBALS['CODE']['CODE_1']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet('action') && $_GET['action'] == "rang") {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_0']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", "Rang" : "' . $adulte->getIdRang() . '"}';
        } else {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_8']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code" : "' . $GLOBALS['CODE']['CODE_1']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet('action') && $_GET['action'] == 'etatcomptes') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            $json = '{"Code" : "' . $GLOBALS['CODE']['CODE_0']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", "Solde" : "' . $adulte->getSolde() . '", "Enfants" : [';
            $enfant = $adulte->getEnfants();
            $arrayLen = count($enfant);
            if (empty($enfant)) {
                echo $json . '], "NbEnfants" : "' . $arrayLen . '"}';
                exit();
            }
            for ($i = 0; $i < $arrayLen; $i++) {
                $json .= $enfant[$i]->jsonSerialize();
                if ($i < $arrayLen - 1)
                    $json .= ', ';
            }
            $json .= '], "NbEnfants" : "' . $arrayLen . '"}';
            echo $json;
        } else {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_8']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code" : "' . $GLOBALS['CODE']['CODE_1']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet('action') && $_GET['action'] == 'deco') {
    if (isPostSet('login')) {
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if(!is_null($adulte))
            $adulte->deleteToken();
        echo '{"Code" : "' . $GLOBALS['CODE']['CODE_0']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '"}';
    } else {
        echo '{"Code" : "' . $GLOBALS['CODE']['CODE_1']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
}else{
    header('Location: /api/');
}