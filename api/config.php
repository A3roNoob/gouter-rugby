<?php
$file = realpath(dirname(__FILE__) . "/credentials.json");
$data = array();
if (file_exists($file)) {
    $credentials = file_get_contents($file, FILE_USE_INCLUDE_PATH);
    $data = json_decode($credentials, true);
} else {
    echo '{"Code" : 4, "Message" : "Param&egrave;tres de connexion a la base de donn&eacute;e inexistants."}';
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
    "CODE_403" => array("Code" => 403,
        "Message" => "Acc&eacute;s interdit."),
    "Code_501" => array("Code" => 501,
        "Message" => "Not implemented")
);
