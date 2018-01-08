<?php
require_once("config.php");
require_once("functions.php");
if (isGetSet("action") && $_GET['action'] == "enregistrercourse") {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('produits') && isPostSet('montant') && isPostSet('date')) {
                if ($adulte->getIdRang() < 3) {
                    $course = new Course();
                    $date = DateTime::createFromFormat("d/m/Y", test_input($_POST["date"]));
                    if(is_bool($date)){
                        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_19']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_19']['Message'] . '"}';
                        exit(1);
                    }
                    $course->setDate($date);
                    $course->setMontant(test_input($_POST['montant']));
                    $course->enregistrerProduits(stripslashes($_POST["produits"]));
                    $course->setId($adulte->getIdAdulte());
                    $course->validerCourse();
                    echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '"}';
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