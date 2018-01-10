<?php
require_once("config.php");
require_once("functions.php");
if (isGetSet("action") && $_GET['action'] == "enregistrerconsommation") {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('produits') && isPostSet('idenfant')) {
                if ($adulte->getIdRang() < 3) {
                    $enfant = Enfant::loadById(test_input($_POST['idenfant']));

                    $cons = new Consommation();
                    $cons->enregistrerConsommation(stripslashes($_POST["produits"]));
                    $cons->calculerTotal();
                    $cons->setEnfant($enfant);
                    $cons->setDate(DateTime::createFromFormat("d/m/Y H:m:s", date("d/m/Y H:m:s")));
                    $cons->checkAllergies();
                    $cons->validerConsommation();
                    $enfant->loadSolde();
                    echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", "Solde": '.$enfant->getSolde().'}';
                } else {
                    echo '{"Code" : ' . $GLOBALS['CODE']['CODE_403']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_403']['Message'] . '"}';
                }
            } else {
                echo '{"Code" : ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
            }
        } else {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
}else {
    header('Location: /api/');
}