<?php
require_once("config.php");
require_once("functions.php");
if (isGetSet("action") && $_GET['action'] == "connexion") {
    if (isPostSet('login') && isPostSet('mdp')) {
        $login = test_input($_POST['login']);
        $mdp = test_input($_POST['mdp']);
        $user = Adulte::Connexion($login, $mdp);
        $user->generateToken();
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", "Nom" : "' . $user->getNom() . '", "Prenom" : "' . $user->getPrenom() . '", "Login" : "' . $user->getMail() . '", "Token" : "' . $user->getToken() . '"}';
    } else {
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet("action") && $_GET['action'] == "auth") {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            echo '{"Code": ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '"}';
        } else {
            echo '{"Code": ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet('action') && $_GET['action'] == "rang") {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            echo '{"Code": ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", "Rang" : "' . $adulte->getIdRang() . '"}';
        } else {
            echo '{"Code": ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet('action') && $_GET['action'] == 'etatcomptes') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            $json = '{"Code": ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", "Solde" : "' . $adulte->getSolde() . '", "Enfants" : [';
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
            echo '{"Code": ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet('action') && $_GET['action'] == 'deco') {
    if (isPostSet('login')) {
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if (!is_null($adulte))
            $adulte->deleteToken();
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '"}';
    } else {
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet('action') && $_GET['action'] == 'enfants') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if ($adulte->getIdRang() < 3) {
                $enfants = new EnfantHandler();
                echo '{"Code": ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", ' . $enfants->jsonSerialize() . '}';
            } else {
                echo '{"Code": ' . $GLOBALS['CODE']['CODE_403']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_403']['Message'] . '"}';
            }

        } else {
            echo '{"Code": ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet('action') && $_GET['action'] == 'ajouterallergie') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('idenfant') && isPostSet('idallergie')) {
                $enfant = Enfant::loadById(test_input($_POST['idenfant']));
                if ($enfant->getIdParent() == $adulte->getIdAdulte() || $adulte->getIdRang() < 3) {
                    $allergie = Allergie::loadById(test_input($_POST['idallergie']));
                    if ($allergie->getIdAllergene() > 0) {
                        $enfant->ajouterAllergie($allergie);
                        echo '{"Code": ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '"}';
                    } else {
                        echo '{"Code": ' . $GLOBALS['CODE']['CODE_15']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_15']['Message'] . '"}';
                    }
                } else {
                    echo '{"Code": ' . $GLOBALS['CODE']['CODE_403']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_403']['Message'] . '"}';
                }
            } else {
                echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
            }
        } else {
            echo '{"Code": ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
}else if (isGetSet('action') && $_GET['action'] == 'retirerallergie') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('idenfant') && isPostSet('idallergie')) {
                $enfant = Enfant::loadById(test_input($_POST['idenfant']));
                if ($enfant->getIdParent() == $adulte->getIdAdulte() || $adulte->getIdRang() < 3) {
                    $allergie = Allergie::loadById(test_input($_POST['idallergie']));
                    if ($allergie->getIdAllergene() > 0) {
                        if($enfant->checkAllergie($allergie)){
                            $enfant->retirerAllergie($allergie);
                            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '"}';
                        }else {
                            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_16']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_16']['Message'] . '"}';
                        }
                    } else {
                        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_15']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_15']['Message'] . '"}';
                    }
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
} else if (isGetSet('action') && $_GET['action'] == 'checkallergie') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('idenfant') && isPostSet('idallergie')) {
                $enfant = Enfant::loadById(test_input($_POST['idenfant']));
                if ($enfant->getIdParent() == $adulte->getIdAdulte() || $adulte->getIdRang() < 3) {
                    $allergie = Allergie::loadById(test_input($_POST['idallergie']));
                    if ($allergie->getIdAllergene() > 0) {
                        $all = $enfant->checkAllergie($allergie);
                        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", "Allergique": '. (($all) ? '1' : '0') .'}';
                    } else {
                        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_15']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_15']['Message'] . '"}';
                    }
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
} else if (isGetSet('action') && $_GET['action'] == 'enfantallergie') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('idenfant')) {
                $enfant = Enfant::loadById(test_input($_POST['idenfant']));
                if ($enfant->getIdParent() == $adulte->getIdAdulte() || $adulte->getIdRang() < 3) {
                        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", '. $enfant->getAllergies()->jsonSerialize() .'}';

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