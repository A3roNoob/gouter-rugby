<?php
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
}