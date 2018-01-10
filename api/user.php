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
} else if (isGetSet('action') && $_GET['action'] == 'retirerallergie') {
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
                        if ($enfant->checkAllergie($allergie)) {
                            $enfant->retirerAllergie($allergie);
                            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '"}';
                        } else {
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
                        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", "Allergique": ' . (($all) ? '1' : '0') . '}';
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
                    echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", ' . $enfant->getAllergies()->jsonSerialize() . '}';

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
} else if (isGetSet('action') && $_GET['action'] == 'modifierenfant') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('idenfant') && (isPostSet('nom') || isPostSet('prenom') || isPostSet('naissance'))) {
                $enfant = Enfant::loadById(test_input($_POST['idenfant']));
                if ($adulte->getIdRang() < 3) {
                    if (isPostSet('nom'))
                        $enfant->setNom(test_input($_POST['nom']));
                    if (isPostSet('prenom'))
                        $enfant->setPrenom(test_input($_POST['prenom']));
                    if (isPostSet('naissance'))
                        $enfant->setNaissance(test_input($_POST['naissance']));
                    $enfant->update();
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
} else if (isGetSet('action') && $_GET['action'] == 'ajouterenfant') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('idparent') && isPostSet('nom') && isPostSet('prenom') && isPostSet('naissance')) {
                if ($adulte->getIdRang() < 3) {
                    $adulte = Adulte::loadById(test_input($_POST['idparent']));
                    $enfant = Enfant::creerEnfant(test_input($_POST['nom']), test_input($_POST['prenom']), $adulte, test_input($_POST['naissance']));
                    $enfant->enregistrer();
                    echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", "ID" : '.$enfant->getIdEnfant().'}';

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
} else if (isGetSet('action') && $_GET['action'] == 'supprimerenfant') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('idenfant')) {
                if ($adulte->getIdRang() < 3) {
                    $enfant = Enfant::loadById(test_input($_POST['idenfant']));
                    $enfant->supprimer();
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
} else if (isGetSet('action') && $_GET['action'] == 'adultes') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if ($adulte->getIdRang() < 3) {
                $adulte = new AdulteHandler();
                echo '{"Code": ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", ' . $adulte->jsonSerialize() . '}';
            } else {
                echo '{"Code": ' . $GLOBALS['CODE']['CODE_403']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_403']['Message'] . '"}';
            }

        } else {
            echo '{"Code": ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet('action') && $_GET['action'] == 'ajouteradulte') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('idrang') && isPostSet('nom') && isPostSet('prenom') && isPostSet('mail') && isPostSet('tel') && isPostSet('mdp')) {
                if ($adulte->getIdRang() < 2) {
                    $adulte = Adulte::creerAdulte(test_input($_POST['nom']), test_input($_POST['prenom']), Rang::loadById(test_input($_POST['idrang']))->getIdRang(), test_input($_POST['mail']), test_input($_POST['tel']), test_input($_POST['mdp']));
                    $adulte->enregistrer();
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
} else if (isGetSet('action') && $_GET['action'] == 'modifieradulte') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulteDem = Adulte::loadByLogin($login);
        if ($adulteDem->checkToken($token)) {
            if (isPostSet('idadulte') && (isPostSet('nom') || isPostSet('prenom') || isPostSet("idrang") || isPostSet('mail') || isPostSet('tel') || isPostSet('mdp'))) {
                $adulte = Adulte::loadById(test_input($_POST['idadulte']));
                if ($adulteDem->getIdRang() < 2) {
                    if (isPostSet('nom'))
                        $adulte->setNom(test_input($_POST['nom']));
                    if (isPostSet('prenom'))
                        $adulte->setPrenom(test_input($_POST['prenom']));
                    if (isPostSet('mail'))
                        $adulte->setMail(test_input($_POST['mail']));
                    if (isPostSet('tel'))
                        $adulte->setTel(test_input($_POST['tel']));
                    if (isPostSet('mdp'))
                        $adulte->setMdp(md5(test_input($_POST['mdp'])));
                    if (isPostSet('idrang'))
                        $adulte->setIdRang(Rang::loadById(test_input($_POST['idrang']))->getIdRang());
                    $adulte->update();
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
}else if (isGetSet('action') && $_GET['action'] == 'supprimeradulte') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('idadulte')) {
                if ($adulte->getIdRang() < 2) {
                    $adulte = adulte::loadById(test_input($_POST['idadulte']));
                    $adulte->supprimer();
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
} else if (isGetSet('action') && $_GET['action'] == 'rangs') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if ($adulte->getIdRang() < 3) {
                $rang = new RangHandler();
                echo '{"Code": ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", ' . $rang->jsonSerialize() . '}';
            } else {
                echo '{"Code": ' . $GLOBALS['CODE']['CODE_403']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_403']['Message'] . '"}';
            }

        } else {
            echo '{"Code": ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
}else if (isGetSet('action') && $_GET['action'] == 'historiquetransac') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if ($adulte->getIdRang() < 3) {
                $trans = new TransactionHandler();
                if(isPostSet('nbtrans'))
                    $trans->loadOperations(test_input($_POST['nbtrans']));
                else
                    $trans->loadOperations(5);
                echo '{"Code": ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", ' . $trans->jsonSerialize() . '}';
            } else {
                echo '{"Code": ' . $GLOBALS['CODE']['CODE_403']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_403']['Message'] . '"}';
            }

        } else {
            echo '{"Code": ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet('action') && $_GET['action'] == 'historiqueenfanttransac') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if(isPostSet('idenfant')) {
                if ($adulte->getIdRang() < 3) {
                    $trans = new TransactionHandler();
                    if (isPostSet('nbtrans'))
                        $trans->loadEnfantOperations(test_input($_POST['nbtrans']), test_input($_POST['idenfant']));
                    else
                        $trans->loadEnfantOperations(5, test_input($_POST['idenfant']));
                    echo '{"Code": ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", ' . $trans->jsonSerialize() . '}';
                } else {
                    echo '{"Code": ' . $GLOBALS['CODE']['CODE_403']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_403']['Message'] . '"}';
                }
            }else {
                echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
            }
        } else {
            echo '{"Code": ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code": ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else {
    header('Location: /api/');
}