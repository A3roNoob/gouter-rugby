<?php
require_once("config.php");
require_once("functions.php");
if (isGetSet('action') && $_GET['action'] == "produits") {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            $prodMan = new ProduitHandler();
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", ' . $prodMan->jsonSerialize() . '}';
        } else {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if (isGetSet('action') && $_GET['action'] == 'allergies') {
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            $allMan = new AllergieHandler();
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '", ' . $allMan->jsonSerialize() . '}';
        } else {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
        }
    } else {
        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
    }
} else if(isGetSet('action') && $_GET['action'] == 'supprimer'){
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('idproduit')) {
                if ($adulte->getIdRang() < 3) {
                    $produit = new Produit();
                    $produit->setIdProduit(test_input($_POST['idproduit']));
                    $produit->supprimerProduit();
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
}else if(isGetSet('action') && $_GET['action'] == 'ajouter'){
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('nom') && isPostSet("desc") && isPostSet("prix")) {
                if ($adulte->getIdRang() < 3) {
                    $produit = Produit::creerProduit(test_input($_POST["nom"]), test_input($_POST['desc']), test_input($_POST['prix']));
                    $produit->enregistrerProduit();
                    echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . ', "id" : '.$produit->getIdProduit().'}';
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
}else if(isGetSet('action') && $_GET['action'] == 'modifier'){
    if (isPostSet('token') && isPostSet('login')) {
        $token = test_input($_POST['token']);
        $login = test_input($_POST['login']);
        $adulte = Adulte::loadByLogin($login);
        if ($adulte->checkToken($token)) {
            if (isPostSet('idproduit') && (isPostSet('nom') || isPostSet("desc") || isPostSet("prix"))) {
                if ($adulte->getIdRang() < 3) {
                    $produit = Produit::loadProduit(test_input($_POST['idproduit']));
                    if(is_bool($produit)){
                        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_18']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_18']['Message'] . '", "IDS" : [' . $_POST['idproduit'] . ']}';
                        exit(1);
                    }
                    if(isPostSet('nom'))
                        $produit->setNom(test_input($_POST['nom']));
                    if(isPostSet('desc'))
                        $produit->setDescProduit(test_input($_POST['desc']));
                    if(isPostSet('prix'))
                        $produit->setPrix(test_input($_POST['prix']));
                    $produit->updateProduit();
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