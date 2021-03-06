<?php
$file = realpath(dirname(__FILE__) . "/credentials.json");
$data = array();
if (file_exists($file)) {
    $credentials = file_get_contents($file, FILE_USE_INCLUDE_PATH);
    $data = json_decode($credentials, true);
} else {
    echo '{"Code" : 1000, "Message" : "Param&egrave;tres de connexion a la base de donn&eacute;e inexistants."}';
    exit(1);
}

$config = array(
    "db" => array(
        "dbname" => $data['dbname'],
        "username" => $data['username'],
        "password" => $data['password'],
        "host" => $data['dbhost']
    )
);

define("CLASS_PATH", realpath(dirname(__FILE__) . '/classes'));
define("LOG_PATH", realpath(dirname(__FILE__) . '/log'));
define("SOLDE_LIMIT", 9999.99);
//Autoloader si on instancie une classe non déclarée
function autoloader($class)
{
    include CLASS_PATH . '/' . $class . '.php';
}

spl_autoload_register('autoloader');
global $CODE;
$GLOBALS['CODE'] = array(
    "CODE_0" => array("Code" => 0,
        "Message" => "Requête effectuée. Pas d'erreur rencontrée côté serveur."),
    "CODE_1" => array("Code" => 1,
        "Message" => "Un ou plusieurs champs manquants."),
    "CODE_2" => array("Code" => 2,
        "Message" => "Login/Mot de passe incorrect(s)."),
    "CODE_3" => array("Code" => 3,
        "Message" => "&Eacute;chec de connexion à la base de donn&eacute;es"),
    "CODE_4" => array("Code" => 4,
        "Message" => "Param&egrave;tres de connexion a la base de donn&eacute;e inexistants."),
    "CODE_5" => array("Code" => 5,
        "Message" => "Echec lors de la requ&ecirc;te &agrave; la base de donn&eacute;es."),
    "CODE_6" => array("Code" => 6,
        "Message" => "Token/mail vide."),
    "CODE_7" => array("Code" => 7,
        "Message" => "Cet e-mail ne correspond &agrave; aucun utilisateur."),
    "CODE_8" => array("Code" => 8,
        "Message" => "Token inconnu."),
    "CODE_9" => array("Code" => 9,
        "Message" => "Solde maximal serait d&eacute;pass&eacute;, limite: " . SOLDE_LIMIT . "&euro;."),
    "CODE_10" => array("Code" => 10,
        "Message" => "Ne peut pas ajouter 0 &euro; &agrave; un solde."),
    "CODE_11" => array("Code" => 11,
        "Message" => "Cet id ne correspond &agrave; aucun enfant."),
    "CODE_12" => array("Code" => 12,
        "Message" => "Ne peut pas transf&eacute;rer d'argent &agrave; un enfant n'existant pas."),
    "CODE_13" => array("Code" => 13,
        "Message" => "N'a pas assez d'argent pour pouvoir transf&eacute;rer"),
    "CODE_14" => array("Code" => 14,
        "Message" => "On ne peut transf&eacute;rer au m&ecirc;me enfant."),
    "CODE_15" => array("Code" => 15,
        "Message" => "Cet id ne correspond &agrave; aucune allergie."),
    "CODE_16" => array("Code" => 16,
        "Message" => "Cet enfant n'a pas cette allergie d'enregistr&acute;e."),
    "CODE_17" => array("Code" => 17,
        "Message" => "Produits manquant dans la trame envoy&eacute;e dans le json."),
    "CODE_18" => array("Code" => 18,
        "Message" => "Ce/ces id ne correspond &agrave; aucun produit."),
    "CODE_19" => array("Code" => 19,
        "Message" => "La date n'est pas correcte (format: dd/mm/YYYY ou en fran&ccirc;ais jj/mm/AAAA)."),
    "CODE_20" => array("Code" => 20,
        "Message" => "Enfant allergique &agrave; certains produits."),
    "CODE_21" => array("Code" => 21,
        "Message" => "Vous ne pouvez pas enregistrer cet enfant, il existe d&eacute;j&agrave; dans la base."),
    "CODE_22" => array("Code" => 22,
        "Message" => "Vous ne pouvez pas enregistrer cet adulte, il existe d&eacute;j&agrave; dans la base."),
    "CODE_23" => array("Code" => 23,
        "Message" => "Cet id ne correspond &agrave; aucun rang."),
    "CODE_403" => array("Code" => 403,
        "Message" => "Acc&eacute;s interdit."),
    "Code_501" => array("Code" => 501,
        "Message" => "Not implemented")
);
