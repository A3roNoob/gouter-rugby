<?php
if(isGetSet('action') && $_GET['action'] == "produits"){
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            $prodMan = new ProduitHandler();
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_0']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", ' . $prodMan->jsonSerialize() . '}';
        } else {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_8']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code" : "' . $GLOBALS['CODE']['CODE_1']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
}