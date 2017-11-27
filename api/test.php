<?php
require_once('config.php');
require_once('functions.php');

echo md5("citrouille");
echo "<br />";
$user = Adulte::Connexion("test@test.ca", "test");
$user->generateToken();
foreach($user->getEnfants() as $enfant)
    echo var_dump($enfant).'<br />';