<?php
require_once("config.php");
require_once("functions.php");

function resetBase()
{
    $db = DatabaseObject::connect();
    $query = $db->prepare("DROP TABLE detailcourse, detailconso, consommation, course, operationsolde;");
    try {
        $query->execute();
    } catch (PDOException $e) {
        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
        exit(1);
    }
    $query1 = $db->prepare("CREATE TABLE operationsolde(idOperation INT(5) PRIMARY KEY AUTO_INCREMENT,idAdulte INT(4),idEnfant INT(4),montant DECIMAL(6,2),dateOpe DATETIME,CONSTRAINT FK_AdulteOperation FOREIGN KEY (idAdulte) REFERENCES adulte(idAdulte) ON DELETE CASCADE, CONSTRAINT FK_EnfantOperation FOREIGN KEY (idEnfant) REFERENCES enfant(idEnfant) ON DELETE CASCADE);");
    $query2 = $db->prepare("CREATE TABLE course(idCourse INT(4) AUTO_INCREMENT,idAdulte INT(4),total DECIMAL(6,2) DEFAULT 0,dateCourse DATE,CONSTRAINT PK_Course PRIMARY KEY(idCourse,idAdulte),CONSTRAINT FK_AdulteCourse FOREIGN KEY (idAdulte) REFERENCES adulte(idAdulte) ON DELETE CASCADE);");
    $query3 = $db->prepare("CREATE TABLE consommation(    idConso INT(5) AUTO_INCREMENT,    idEnfant INT(4),    total DECIMAL(6,2),    dateConso DATETIME, CONSTRAINT PK_Consommation PRIMARY KEY (idConso,idEnfant),    CONSTRAINT FK_EnfantConso FOREIGN KEY (idEnfant) REFERENCES enfant(idEnfant) ON DELETE CASCADE);");
    $query4 = $db->prepare("CREATE TABLE detailconso(    idConso INT(5),    idProduit INT(4),    quantite INT(2) DEFAULT 0,    CONSTRAINT PK_DetailConso PRIMARY KEY (idConso,idProduit),    CONSTRAINT FK_ConsoDetailConso FOREIGN KEY (idConso) REFERENCES consommation(idConso) ON DELETE CASCADE,    CONSTRAINT FK_ProduitDetailConso FOREIGN KEY (idProduit) REFERENCES produit(idProduit) ON DELETE CASCADE);");
    $query5 = $db->prepare("CREATE TABLE detailcourse(    idCourse INT(4),    idProduit INT(4),    quantite INT(3),    CONSTRAINT PK_DetailCourse PRIMARY KEY (idCourse,idProduit),    CONSTRAINT FK_ProduitDetailCourse FOREIGN KEY (idCourse) REFERENCES course(idCourse) ON DELETE CASCADE,    CONSTRAINT FK_ProduitDetailProduit FOREIGN KEY (idProduit) REFERENCES produit(idProduit) ON DELETE CASCADE);");
    try {
        $query1->execute();
        $query2->execute();
        $query3->execute();
        $query4->execute();
        $query5->execute();
    } catch (PDOException $e) {
        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
        exit(1);
    }
}

if (isPostSet('token') && isPostSet('login')) {
    $token = test_input($_POST['token']);
    $login = test_input($_POST['login']);
    $adulte = Adulte::loadByLogin($login);
    if ($adulte->checkToken($token) && $adulte->getIdRang() < 2) {
        resetBase();
        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_0']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_0']['Message'] . '"}';
    } else {
        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_8']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_8']['Message'] . '"}';
    }
} else {
    echo '{"Code" : ' . $GLOBALS['CODE']['CODE_1']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_1']['Message'] . '"}';
}
