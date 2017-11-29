<?php
require_once("config.php");
require_once("functions.php");
if (isGetSet('action') && $_GET['action'] == 'compte') {
    if (isPostSet('token') && isPostSet('login')) {
        if (isGetSet('idadulte')) {
            $token = test_input($_POST['token']);
            $login = test_input($_POST['login']);
            $adulte = Adulte::loadByLogin($login);
            if ($adulte->checkToken($token)) {
                if ($adulte->getIdAdulte() == $_GET['idadulte'] || $adulte->getIdRang() < 3) {
                    if ($adulte->getIdAdulte() != $_GET['idadulte'])
                        $adulte = Adulte::loadById(test_input($_GET['idadulte']));
                    if (isGetSet('idenfant')) {
                        $json = '{"Code" : "' . $GLOBALS['CODE']['CODE_0']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", "Solde" : "' . $adulte->getSolde() . '", "Enfants" : [';
                        $enfant = $adulte->getEnfantById(test_input($_GET['idenfant']));
                        if (!is_null($enfant))
                            $json .= $enfant->jsonSerialize();
                        $json .= ']}';
                        echo $json;
                    } else {
                        echo '{"Code" : "' . $GLOBALS['CODE']['CODE_0']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", "Solde" : "' . $adulte->getSolde() . '", "Enfants" : []}';
                    }
                } else {
                    echo '{"Code" : "' . $GLOBALS['CODE']['CODE_403']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_403']['Message'] . '"}';
                }
            } else {
                echo '{"Code" : "' . $GLOBALS['CODE']['CODE_8']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
            }
        } else {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_1']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
        }
    } else {
        echo '{"Code" : "' . $GLOBALS['CODE']['CODE_1']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet('action') && $_GET['action'] == 'fonds') {
    if (isPostSet('token') && isPostSet('login')) {
        if (isPostSet('idadulte')) {
            $token = test_input($_POST['token']);
            $login = test_input($_POST['login']);
            $adulte = Adulte::loadByLogin($login);
            if ($adulte->checkToken($token)) {
                if (isPostSet('idenfant')) {
                    $enfant = Enfant::loadById(test_input($_POST['idenfant']));
                    if ($enfant->getIdParent() == $adulte->getIdAdulte() || $adulte->getIdRang() < 3){
                        if (isPostSet('somme')) {
                            if ($_POST['somme'] > 0) {
                                $json = '{"Code" : "' . $GLOBALS['CODE']['CODE_0']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '"}';
                                if (!is_null($enfant))
                                    $enfant->ajouterSolde(test_input($_POST['somme']));
                                echo $json;
                                exit(1);
                            } else {
                                echo '{"Code" : "' . $GLOBALS['CODE']['CODE_10']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_10']['Message'] . '"}';
                            }
                        } else {
                            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_1']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
                        }
                    } else {
                        echo '{"Code" : "' . $GLOBALS['CODE']['CODE_403']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_403']['Message'] . '"}';
                    }
                } else {
                    echo '{"Code" : "' . $GLOBALS['CODE']['CODE_403']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_403']['Message'] . '"}';
                }
            } else {
                echo '{"Code" : "' . $GLOBALS['CODE']['CODE_8']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
            }
        } else {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_1']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
        }
    } else {
        echo '{"Code" : "' . $GLOBALS['CODE']['CODE_1']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else {
    header('Location: /api/');
}